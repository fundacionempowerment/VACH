<?php

namespace app\components\graph;

use app\models\Team;
use Yii;
use app\models\Person;
use app\models\Wheel;
use app\models\WheelQuestion;

class Relations
{

    const RELATION_width = 1800;
    const RELATION_height = 1000;
    const RELATION_imageSize = 60;
    const RELATION_margin = 100;
    const RELATION_arrow_x_ring = 90;
    const RELATION_text_height = 12;
    // This code assumes square proto human image
    const RELATION_arrow_y_ring = self::RELATION_arrow_x_ring;
    const RELATION_xradio = self::RELATION_width / 4 - self::RELATION_imageSize / 2 - self::RELATION_margin;
    const RELATION_yradio = self::RELATION_xradio;
    const RELATION_image_text_height = self::RELATION_imageSize + self::RELATION_text_height;

    static public function draw($teamId, $memberId, $wheelType)
    {
        $data = Wheel::getRelationsMatrix($teamId, $wheelType);

        $member = Person::findOne(['id' => $memberId]);

        $team = Team::findOne($teamId);
        $title = Yii::t('dashboard', 'Relation Matrix') . ' ' .
            ($wheelType == 1 ? $team->teamType->level_1_name : $team->teamType->level_2_name);

        if (!empty($member)) {
            $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
        } else {
            $title .= ' ' . Yii::t('app', 'of the team');
        }

        $forwardData = [];
        foreach ($data as $datum) {
            if ($datum['observer_id'] == $memberId && $datum['observed_id'] == $memberId)
                $forwardData[] = $datum;
        }

        foreach ($data as $datum) {
            if ($datum['observer_id'] != $memberId && $datum['observed_id'] == $memberId)
                $forwardData[] = $datum;
        }

        $backwardData = [];
        foreach ($data as $datum) {
            if ($datum['observer_id'] == $memberId && $datum['observed_id'] == $memberId)
                $backwardData[] = $datum;
        }

        foreach ($data as $datum) {
            if ($datum['observer_id'] == $memberId && $datum['observed_id'] != $memberId)
                $backwardData[] = $datum;
        }

        // Lets draw

        require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvas.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvtools.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_iconplot.php");

        // Setup a basic canvas we can work
        $g = new \CanvasGraph(self::RELATION_width, self::RELATION_height);
        $g->SetFrame(false);

        // We need to stroke the plotarea and margin before we add the
        // text since we otherwise would overwrite the text.
        $g->InitFrame();

        // texts

        $t = new \Text($title, self::RELATION_width / 2, 5);
        $t->SetFont(FF_COOL, FS_BOLD, 28);
        $t->Align('center', 'top');
        $t->SetColor("black");
        $t->Stroke($g->img);

        self::drawForwardRelations($g, $forwardData);
        self::drawBackwardRelations($g, $backwardData);

        // Stroke the graph
        $g->Stroke();
        exit();
    }

    static private function drawForwardRelations($g, $data)
    {
        for ($i = 1; $i < count($data); $i++) {
            $current_angle = ($i - 1) * 2 * pi() / (count($data) - 1);

            if ($data[$i]['value'] < 4 / 3) {
                $g->img->SetColor('#d9534f');
            } else if ($data[$i]['value'] > 4 * 2 / 3) {
                $g->img->SetColor('#5cb85c');
            } else {
                $g->img->SetColor('#f0ad4e');
            }

            $x1 = self::RELATION_width / 4 + (self::RELATION_xradio - self::RELATION_arrow_x_ring) * cos($current_angle);
            $y1 = self::RELATION_height / 2 + (self::RELATION_yradio - self::RELATION_arrow_y_ring) * sin($current_angle);
            $x2 = self::RELATION_width / 4 + self::RELATION_arrow_x_ring * cos($current_angle);
            $y2 = self::RELATION_height / 2 + self::RELATION_arrow_y_ring * sin($current_angle);
            self::drawArrow($g, $x1, $y1, $x2, $y2);
        }

        self::drawPersons($g, $data, 'observer');
    }

    static private function drawBackwardRelations($g, $data)
    {
        for ($i = 1; $i < count($data); $i++) {
            $current_angle = ($i - 1) * 2 * pi() / (count($data) - 1);

            if ($data[$i]['value'] < 4 / 3) {
                $g->img->SetColor('#d9534f');
            } else if ($data[$i]['value'] > 4 * 2 / 3) {
                $g->img->SetColor('#5cb85c');
            } else {
                $g->img->SetColor('#f0ad4e');
            }

            $x1 = self::RELATION_width * 3 / 4 + self::RELATION_arrow_x_ring * cos($current_angle);
            $y1 = self::RELATION_height / 2 + self::RELATION_arrow_y_ring * sin($current_angle);
            $x2 = self::RELATION_width * 3 / 4 + (self::RELATION_xradio - self::RELATION_arrow_x_ring) * cos($current_angle);
            $y2 = self::RELATION_height / 2 + (self::RELATION_yradio - self::RELATION_arrow_y_ring) * sin($current_angle);

            self::drawArrow($g, $x1, $y1, $x2, $y2);
        }

        self::drawPersons($g, $data, 'observed');
    }

    static private function drawArrow($g, $x1, $y1, $x2, $y2)
    {
        $g->img->SetLineWeight(6);

        $headlen = 10; // length of head in pixels
        $angle = atan2($y2 - $y1, $x2 - $x1);

        $g->img->Line($x1, $y1, $x2, $y2);
        $g->img->Line($x2 - $headlen * cos($angle - pi() / 6), $y2 - $headlen * sin($angle - pi() / 6), $x2, $y2);
        $g->img->Line($x2 - $headlen * cos($angle + pi() / 6), $y2 - $headlen * sin($angle + pi() / 6), $x2, $y2);
    }

    static private function drawPersons($g, $data, $who)
    {
        $maleIcon = new \IconPlot(Yii::getAlias("@app/web/images/protoMale.png"));
        $maleIcon->SetAnchor('center', 'bottom');
        $femaleIcon = new \IconPlot(Yii::getAlias("@app/web/images/protoFemale.png"));
        $femaleIcon->SetAnchor('center', 'bottom');
        $otherIcon = new \IconPlot(Yii::getAlias("@app/web/images/protoOther.png"));
        $otherIcon->SetAnchor('center', 'bottom');

        for ($i = 1; $i < count($data); $i++) {
            $current_angle = ($i - 1) * 2 * pi() / (count($data) - 1);
            // draw member image

            $x = self::RELATION_width * ($who == 'observer' ? 1 : 3) / 4 + self::RELATION_xradio * cos($current_angle);
            $y = self::RELATION_height / 2 + self::RELATION_yradio * sin($current_angle) + 6;

            if ($data[$i][$who . '_gender'] == 0) {
                $maleIcon->SetPos($x, $y);
                $maleIcon->Stroke($g->img);
            } elseif ($data[$i][$who . '_gender'] == 1) {
                $femaleIcon->SetPos($x, $y);
                $femaleIcon->Stroke($g->img);
            } else {
                $otherIcon->SetPos($x, $y);
                $otherIcon->Stroke($g->img);
            }

            $t = new \Text($data[$i][$who . '_name'] . ' ' . $data[$i][$who . '_surname'], $x, $y + 4);
            $t->SetFont(FF_COOL, FS_NORMAL, 20);
            $t->Align('center', 'top');
            $t->SetColor("black");
            $t->Stroke($g->img);

            $x = self::RELATION_width * ($who == 'observer' ? 1 : 3) / 4 + self::RELATION_xradio / 2 * cos($current_angle);
            $y = self::RELATION_height / 2 + self::RELATION_yradio / 2 * sin($current_angle);

            $t = new \Text(round($data[$i]['value'] * 1000 / 4) / 10 . '%', $x, $y);
            $t->SetFont(FF_COOL, FS_BOLD, 20);
            $t->Align('center', 'center');
            $t->SetColor("black");
            $t->Stroke($g->img);
        }

        // draw central image
        $x = self::RELATION_width * ($who == 'observer' ? 1 : 3) / 4;
        $y = self::RELATION_height / 2 + 6;

        if (count($data) > 0) {

            if ($data[0][$who . '_gender'] == 0) {
                $maleIcon->SetPos($x, $y);
                $maleIcon->Stroke($g->img);
            } elseif ($data[0][$who . '_gender'] == 1) {
                $femaleIcon->SetPos($x, $y);
                $femaleIcon->Stroke($g->img);
            } else {
                $otherIcon->SetPos($x, $y);
                $otherIcon->Stroke($g->img);
            }

            $t = new \Text($data[0][$who . '_name'] . ' ' . $data[0][$who . '_surname'], $x, $y + 4);
            $t->SetFont(FF_COOL, FS_NORMAL, 20);
            $t->Align('center', 'top');
            $t->SetColor("black");

            $t->Stroke($g->img);
        }
    }

}
