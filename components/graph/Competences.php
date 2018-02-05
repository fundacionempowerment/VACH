<?php

namespace app\components\graph;

use Yii;
use app\models\Person;
use app\models\Wheel;
use app\models\WheelQuestion;

class Competences
{

    const width = 2000;
    const height = 600;
    const margin_title = 64;
    const margin_subtitle = 50;
    const margin_inner = 20;
    const cells = [
        ['dimension' => 0, 'row' => 0, 'col' => 0],
        ['dimension' => 1, 'row' => 0, 'col' => 1],
        ['dimension' => 2, 'row' => 0, 'col' => 2],
        ['dimension' => 3, 'row' => 1, 'col' => 0],
        ['dimension' => 4, 'row' => 1, 'col' => 1],
        ['dimension' => 5, 'row' => 1, 'col' => 2],
        ['dimension' => 6, 'row' => 2, 'col' => 0],
        ['dimension' => 7, 'row' => 2, 'col' => 1],
    ];

    static public function draw($teamId, $memberId, $wheelType)
    {
        $minValue = 10000;
        $maxValue = -10000;

        if ($memberId > 0) {
            $competences = Wheel::getMemberCompetences($teamId, $memberId, $wheelType);
        } else {
            $competences = Wheel::getPerceptions($teamId, $wheelType);
        }

        foreach ($competences as $gauge) {
            if ($gauge > $maxValue) {
                $maxValue = $gauge;
            }
            if ($gauge < $minValue) {
                $minValue = $gauge;
            }
        }

        $dimensions = WheelQuestion::getDimensionNames($wheelType, true);

        if ($wheelType == Wheel::TYPE_GROUP)
            $title = Yii::t('dashboard', 'Group Competence Matrix');
        else if ($wheelType == Wheel::TYPE_ORGANIZATIONAL)
            $title = Yii::t('dashboard', 'Organizational Competence Matrix');
        else
            $title = Yii::t('dashboard', 'Individual Competence Matrix');

        $member = Person::findOne(['id' => $memberId]);

        if (!empty($member)) {
            $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
        } else {
            $title .= ' ' . Yii::t('app', 'of the team');
        }

        require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvas.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvtools.php");

        // Setup a basic canvas we can work
        $g = new \CanvasGraph(self::width, self::height);
        $g->SetFrame(false);

        // We need to stroke the plotarea and margin before we add the
        // text since we otherwise would overwrite the text.
        $g->InitFrame();

        // title

        $t = new \Text($title, self::width / 2, 5);
        $t->SetFont(FF_COOL, FS_BOLD, 40);
        $t->Align('center', 'top');
        $t->SetColor("black");
        $t->Stroke($g->img);

        for ($i = 0; $i < count($competences); $i++) {
            self::drawGauge($g, $competences[$i], self::cells[$i], $wheelType, $minValue, $maxValue);
        }

        $g->Stroke();
        exit();
    }

    static private function drawGauge($g, $value, $cell, $wheelType, $minValue, $maxValue)
    {
        $x1 = self::width * $cell['col'] / 3;
        $x2 = self::width * ($cell['col'] + 1) / 3;

        $y1 = (self::height - self::margin_title) * $cell['row'] / 3 + self::margin_title;
        $y3 = (self::height - self::margin_title) * ($cell['row'] + 1) / 3 + self::margin_title;
        $y2 = $y1 + self::margin_subtitle;

        $ytitle = $y1 + 3;

        // Write dimensions

        $text = WheelQuestion::getDimentionName($cell['dimension'], Wheel::TYPE_INDIVIDUAL, true) . ' - ';
        $t = new \Text($text, $x1 + self::margin_inner, $ytitle);
        $t->SetFont(FF_COOL, ($wheelType == Wheel::TYPE_INDIVIDUAL ? FS_BOLD : FS_NORMAL), 20);
        $t->Align('left', 'top');
        $t->SetColor($wheelType == Wheel::TYPE_INDIVIDUAL ? "black" : "gray7");
        $t->Stroke($g->img);

        $x_next = $x1 + self::margin_inner + $t->GetWidth($g->img);

        $text = WheelQuestion::getDimentionName($cell['dimension'], Wheel::TYPE_GROUP, true) . ' - ';
        $t = new \Text($text, $x_next + 2, $ytitle);
        $t->SetFont(FF_COOL, FS_NORMAL, 20);
        $t->Align('left', 'top');
        $t->SetColor($wheelType == Wheel::TYPE_GROUP ? "black" : "gray7" );
        $t->Stroke($g->img);

        $x_next = $x_next + 2 + $t->GetWidth($g->img);

        $text = WheelQuestion::getDimentionName($cell['dimension'], Wheel::TYPE_ORGANIZATIONAL, true);
        $t = new \Text($text, $x_next + 2, $ytitle);
        $t->SetFont(FF_COOL, ($wheelType == Wheel::TYPE_ORGANIZATIONAL ? FS_BOLD : FS_NORMAL), 20);
        $t->Align('left', 'top');
        $t->SetColor($wheelType == Wheel::TYPE_ORGANIZATIONAL ? "black" : "gray7");
        $t->Stroke($g->img);

        // Draw rectangles

        if ($value > Yii::$app->params['good_consciousness']) {
            $color = '#5cb85c';
        } else if ($value < Yii::$app->params['minimal_consciousness']) {
            $color = '#d9534f';
        } else {
            $color = '#f0ad4e';
        }

        $percentage = $value / 4 * 100;
        if ($percentage < 5) {
            $vv = 5;
        } else {
            $vv = $percentage;
        }

        $x = $vv / 100 * ($x2 - $x1 - 2 * self::margin_inner) + $x1 + self::margin_inner;

        $g->img->SetColor($color);
        $g->img->FilledRectangle($x1 + self::margin_inner, $y2, $x, $y3 - self::margin_inner * 2);

        $g->img->SetColor('black');
        $g->img->Rectangle($x1 + self::margin_inner, $y2, $x2 - self::margin_inner, $y3 - self::margin_inner * 2);

        if ($value == $minValue) {
            $g->img->SetColor('#ce8483');
        } else if ($value == $maxValue) {
            $g->img->SetColor('#67b168');
        }
        if ($value == $minValue || $value == $maxValue) {
            for($i = 1; $i <= 6; $i++){
            $g->img->Rectangle($x1 + self::margin_inner - $i, $y2 - $i, $x2 - self::margin_inner + $i, $y3 - self::margin_inner * 2 + $i);
            }
        }

        // Percentage

        $t = new \Text(round($percentage, 1) . '%', $x1 + self::margin_inner + 10, ($y2 + $y3 - self::margin_inner * 2) / 2);
        $t->SetFont(FF_COOL, FS_BOLD, 26);
        $t->Align('left', 'center');
        $t->SetColor("white");
        $t->Stroke($g->img);
    }

}
