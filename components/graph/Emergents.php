<?php

namespace app\components\graph;

use Yii;
use app\models\Person;
use app\models\Wheel;
use app\models\WheelQuestion;
use app\models\Question;

class Emergents
{

    const width = 2000;
    const line_height = 88;
    const margin_title = 68;
    const height_subtitle = 36;
    const margin_inner = 16;
    const max_emergent_shown = 8;

    static private $dimensions;
    static private $value_key;
    static private $member;
    static private $team;
    static private $company;

    static public function draw($teamId, $memberId, $wheelType)
    {
        if ($wheelType == Wheel::TYPE_GROUP)
            $title = Yii::t('dashboard', 'Group Emergents Matrix');
        else if ($wheelType == Wheel::TYPE_ORGANIZATIONAL)
            $title = Yii::t('dashboard', 'Organiz. Emergents Matrix');
        else
            $title = Yii::t('dashboard', 'Individual Emergents Matrix');

        self::$team = \app\models\Team::findOne(['id' => $teamId]);
        self::$company = self::$team->company;
        self::$member = Person::findOne(['id' => $memberId]);

        if (!empty(self::$member)) {
            $title .= ' ' . Yii::t('app', 'of') . ' ' . self::$member->fullname;
        } else {
            $title .= ' ' . Yii::t('app', 'of the team');
        }

        if ($memberId > 0) {
            $emergents = Wheel::getMemberEmergents($teamId, $memberId, $wheelType);
        } else {
            $emergents = Wheel::getEmergents($teamId, $wheelType);
        }

        $max_emergents = [];
        $min_emergents = [];
        $gap_emergents = [];

        $maxValue = -100;
        $minValue = 100;
        $maxGap = -100;

        self::$value_key = $wheelType == Wheel::TYPE_INDIVIDUAL ? 'mine_value' : 'value';
        $questionCount = WheelQuestion::getQuestionCount($wheelType) / 8;

        foreach ($emergents as $emergent)
            if ($emergent['answer_order'] % $questionCount != $questionCount - 1) {
                if ($emergent[self::$value_key] > $maxValue) {
                    $maxValue = $emergent[self::$value_key];
                }
                if ($emergent[self::$value_key] < $minValue) {
                    $minValue = $emergent[self::$value_key];
                }
            }

        foreach ($emergents as $emergent) {
            if ($emergent['answer_order'] % $questionCount != $questionCount - 1) {
                if ($emergent[self::$value_key] == $maxValue) {
                    $max_emergents[] = $emergent;
                }
                if ($emergent[self::$value_key] == $minValue) {
                    $min_emergents[] = $emergent;
                }
            }
        }

        if ($wheelType > Wheel::TYPE_INDIVIDUAL && $memberId > 0) {
            foreach ($emergents as $emergent) {
                if ($emergent['answer_order'] % $questionCount != $questionCount - 1) {
                    $gap = abs($emergent['mine_value'] - $emergent['value']);
                    if ($gap > $maxGap) {
                        $maxGap = $gap;
                    }
                }
            }

            foreach ($emergents as $emergent) {
                if ($emergent['answer_order'] % $questionCount != $questionCount - 1) {
                    $gap = abs($emergent['mine_value'] - $emergent['value']);
                    if ($gap == $maxGap) {
                        $gap_emergents[] = $emergent;
                    }
                }
            }
        }

        while (count($max_emergents) > self::max_emergent_shown) {
            array_shift($max_emergents);
        }
        while (count($min_emergents) > self::max_emergent_shown) {
            array_shift($min_emergents);
        }
        while (count($gap_emergents) > self::max_emergent_shown) {
            array_shift($gap_emergents);
        }

        self::$dimensions = WheelQuestion::getDimensionNames($wheelType, true);

        require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvas.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvtools.php");

        // Setup a basic canvas we can work
        $height = self::margin_title;
        $height += count($max_emergents) * (self::line_height + self::margin_inner / 2 + self::height_subtitle) + (count($max_emergents) > 0 ? self::height_subtitle : 0);
        $height += count($min_emergents) * (self::line_height + self::height_subtitle) + (count($min_emergents) > 0 ? self::height_subtitle : 0);
        $height += count($gap_emergents) * (self::line_height + self::height_subtitle) + (count($gap_emergents) > 0 ? self::height_subtitle : 0);
        $height += self::margin_inner;

        $g = new \CanvasGraph(self::width, $height);
        $g->SetFrame(false);

        // We need to stroke the plotarea and margin before we add the
        // text since we otherwise would overwrite the text.
        $g->InitFrame();

        // title

        $t = new \Text($title, self::width / 2, 5);
        $t->SetFont(FF_COOL, FS_BOLD, 28);
        $t->Align('center', 'top');
        $t->SetColor("black");
        $t->Stroke($g->img);

        $current_y = self::margin_title;

        self::drawSubtitle($g, Yii::t('dashboard', 'Strengths'), $current_y);
        foreach ($max_emergents as $emergent) {
            self::drawGauge($g, $emergent, $current_y);
        }

        self::drawSubtitle($g, Yii::t('dashboard', 'Weaknesses'), $current_y);
        foreach ($min_emergents as $emergent) {
            self::drawGauge($g, $emergent, $current_y);
        }

        if (count($gap_emergents) > 0) {
            self::drawSubtitle($g, Yii::t('dashboard', 'Consciousness gap emergents'), $current_y);
            foreach ($gap_emergents as $emergent) {
                self::drawGap($g, $emergent, $current_y);
            }
        }

        $g->Stroke();
        exit();
    }

    static private function drawSubtitle($g, $text, &$current_y)
    {
        $t = new \Text($text, self::margin_inner, $current_y);
        $t->SetFont(FF_COOL, FS_BOLD, 24);
        $t->Align('left', 'top');
        $t->SetColor("black");
        $t->Stroke($g->img);

        $current_y += self::height_subtitle;
    }

    static private function drawGauge($g, $emergent, &$current_y)
    {
        $text = self::$dimensions[$emergent['dimension']] . ' - ' . Question::getEmergentText($emergent['question'], self::$member, self::$team, self::$company);

        $t = new \Text($text, self::margin_inner, $current_y + self::margin_inner / 2);
        $t->SetFont(FF_COOL, FS_BOLD, 18);
        $t->Align('left', 'top');
        $t->SetColor("black");
        $t->Stroke($g->img);

        $current_y += self::margin_inner / 2 + self::height_subtitle;

        $x1 = 0;
        $x2 = self::width;

        $y1 = $current_y;
        $y2 = $y1 + self::line_height;

        // Draw rectangles

        if ($emergent[self::$value_key] > Yii::$app->params['good_consciousness']) {
            $color = '#5cb85c';
        } else if ($emergent[self::$value_key] < Yii::$app->params['minimal_consciousness']) {
            $color = '#d9534f';
        } else {
            $color = '#f0ad4e';
        }

        $percentage = $emergent[self::$value_key] / 4 * 100;
        if ($percentage < 5) {
            $vv = 5;
        } else {
            $vv = $percentage;
        }

        $x = $vv / 100 * ($x2 - $x1 - 2 * self::margin_inner) + $x1 + self::margin_inner;

        $g->img->SetColor($color);
        $g->img->FilledRectangle($x1 + self::margin_inner, $y1, $x, $y2 - self::margin_inner * 2);

        $g->img->SetColor('black');
        $g->img->Rectangle($x1 + self::margin_inner, $y1, $x2 - self::margin_inner, $y2 - self::margin_inner * 2);

        // Percentage

        $t = new \Text(round($percentage) . '%', $x1 + self::margin_inner + 5, ($y1 + $y2 - self::margin_inner * 2) / 2);
        $t->SetFont(FF_COOL, FS_BOLD, 26);
        $t->Align('left', 'center');
        $t->SetColor("white");
        $t->Stroke($g->img);

        $current_y += self::line_height;
    }

    static private function drawGap($g, $emergent, &$current_y)
    {
        $text = self::$dimensions[$emergent['dimension']] . ' - ' . Question::getEmergentText($emergent['question'], self::$member, self::$team, self::$company);

        $t = new \Text($text, self::margin_inner, $current_y + self::margin_inner / 2);
        $t->SetFont(FF_COOL, FS_BOLD, 18);
        $t->Align('left', 'top');
        $t->SetColor("black");
        $t->Stroke($g->img);

        $current_y += self::margin_inner / 2 + self::height_subtitle;

        $x1 = 0;
        $x2 = self::width;

        $y1 = $current_y;
        $y2 = $y1 + self::line_height / 2 - self::margin_inner;

        // Draw rectangles

        $color = '#4444ff';

        $percentage = $emergent['value'] / 4 * 100;
        if ($percentage < 11) {
            $vv = 11;
        } else {
            $vv = $percentage;
        }

        $x = $vv / 100 * ($x2 - $x1 - 2 * self::margin_inner) + $x1 + self::margin_inner;

        $g->img->SetColor($color);
        $g->img->FilledRectangle($x1 + self::margin_inner, $y1, $x, $y2);

        $g->img->SetColor('black');
        $g->img->Rectangle($x1 + self::margin_inner, $y1, $x2 - self::margin_inner, $y2);

        $t = new \Text(Yii::t('dashboard', 'How they see me') . ' ' . round($percentage) . '%', $x1 + self::margin_inner + 5, ($y1 + $y2) / 2);
        $t->SetFont(FF_COOL, FS_BOLD, 16);
        $t->Align('left', 'center');
        $t->SetColor("white");
        $t->Stroke($g->img);

        $y1 = $current_y + self::line_height / 2 - self::margin_inner;
        $y2 = $y1 + self::line_height / 2 - self::margin_inner;

        $color = '#ff4444';

        $percentage = $emergent['mine_value'] / 4 * 100;
        if ($percentage < 11) {
            $vv = 11;
        } else {
            $vv = $percentage;
        }

        $x = $vv / 100 * ($x2 - $x1 - 2 * self::margin_inner) + $x1 + self::margin_inner;

        $g->img->SetColor($color);
        $g->img->FilledRectangle($x1 + self::margin_inner, $y1, $x, $y2);

        $g->img->SetColor('black');
        $g->img->Rectangle($x1 + self::margin_inner, $y1, $x2 - self::margin_inner, $y2);

        $t = new \Text(Yii::t('dashboard', 'How I see me') . ' ' . round($percentage) . '%', $x1 + self::margin_inner + 5, ($y1 + $y2) / 2);
        $t->SetFont(FF_COOL, FS_BOLD, 16);
        $t->Align('left', 'center');
        $t->SetColor("white");
        $t->Stroke($g->img);


        $current_y += self::line_height;
    }

}
