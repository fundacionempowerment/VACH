<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use app\models\Wheel;
use app\models\TeamMember;
use app\models\Team;
use app\models\Assessment;
use app\models\Company;
use app\models\DashboardFilter;
use app\models\Person;
use app\models\WheelQuestion;

class GraphController extends BaseController
{

    public function actionRadar($assessmentId, $memberId, $wheelType)
    {
        $redWheel = Wheel::getProjectedIndividualWheel($assessmentId, $memberId);
        switch ($wheelType) {
            case Wheel::TYPE_GROUP:
                $blueWheel = Wheel::getReflectedGroupWheel($assessmentId, $memberId);
                break;
            case Wheel::TYPE_ORGANIZATIONAL:
                $blueWheel = Wheel::getReflectedOrganizationalWheel($assessmentId, $memberId);
                break;
        }

        $dimensions = WheelQuestion::getDimensionNames($wheelType, true);
        if ($wheelType > Wheel::TYPE_INDIVIDUAL) {
            $individual_dimensions = WheelQuestion::getDimensionNames(Wheel::TYPE_INDIVIDUAL, true);
            for ($i = 0; $i < count($dimensions); $i++) {
                $dimensions[$i] = $individual_dimensions[$i] . '/' . $dimensions[$i];
            }
        }

        switch ($wheelType) {
            case Wheel::TYPE_INDIVIDUAL:
                $title = Yii::t('dashboard', 'Individual Wheel');
                break;
            case Wheel::TYPE_GROUP:
                $title = Yii::t('dashboard', 'Individual projection toward the group');
                break;
            case Wheel::TYPE_ORGANIZATIONAL:
                $title = Yii::t('dashboard', 'Individual projection toward the organization');
                break;
        }

        $member = Person::findOne(['id' => $memberId]);

        if (!empty($member)) {
            $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
        }

        require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_radar.php");

        $graph = new \RadarGraph(700, 400);
        $graph->title->Set($title);
        $graph->title->SetFont(FF_DV_SANSSERIF, FS_BOLD, 16);
        $graph->SetColor('white');
        $graph->SetSize(0.65);
        $graph->SetCenter(0.5, 0.55);
        $graph->SetScale('lin', 0, 4);
        $graph->yscale->ticks->Set(4, 1);
        $graph->SetFrame(false);

        $graph->axis->SetFont(FF_DV_SANSSERIF, FS_BOLD);
        $graph->axis->SetWeight(1);

        // Uncomment the following lines to also show grid lines.
        $graph->grid->SetLineStyle('dashed');
        $graph->grid->SetColor('navy@0.5');
        $graph->grid->Show();

        $graph->ShowMinorTickMarks();

        $graph->SetTitles($dimensions);

        $redPlot = new \RadarPlot($redWheel);
        $redPlot->SetLegend(Yii::t('dashboard', 'How I see me'));
        $redPlot->SetColor('red');
        $redPlot->SetFillColor('lightred@0.5');
        $redPlot->SetLineWeight(3);

        $graph->Add($redPlot);

        if (!empty($blueWheel)) {
            $bluePlot = new \RadarPlot($blueWheel);
            $bluePlot->SetLegend(Yii::t('dashboard', 'How they see me'));
            $bluePlot->SetColor('blue');
            $bluePlot->SetFillColor('lightblue@0.5');
            $bluePlot->SetLineWeight(3);

            $graph->Add($bluePlot);
        }

        $graph->Stroke();
    }

    public function actionLineal($assessmentId, $memberId, $wheelType)
    {
        switch ($wheelType) {
            case Wheel::TYPE_GROUP:
                $redLine = Wheel::getProjectedGroupWheel($assessmentId, $memberId);
                $blueLine = Wheel::getReflectedGroupWheel($assessmentId, $memberId);
                break;
            case Wheel::TYPE_ORGANIZATIONAL:
                $redLine = Wheel::getProjectedOrganizationalWheel($assessmentId, $memberId);
                $blueLine = Wheel::getReflectedOrganizationalWheel($assessmentId, $memberId);
                break;
        }

        $dimensions = WheelQuestion::getDimensionNames($wheelType, true);

        switch ($wheelType) {
            case Wheel::TYPE_INDIVIDUAL:
                $title = Yii::t('dashboard', 'Individual Perception Adjustment Matrix');
                break;
            case Wheel::TYPE_GROUP:
                $title = Yii::t('dashboard', 'Group Perception Adjustment Matrix');
                break;
            case Wheel::TYPE_ORGANIZATIONAL:
                $title = Yii::t('dashboard', 'Organizational Perception Adjustment Matrix');
                break;
        }

        $member = Person::findOne(['id' => $memberId]);

        if (!empty($member)) {
            $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
        }

        require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_line.php");

        $graph = new \Graph(900, 400);

        $graph->title->Set($title);
        $graph->title->SetFont(FF_DV_SANSSERIF, FS_BOLD, 16);
        $graph->SetScale('textlin', 0, 4);
        $graph->SetColor('white');
        $graph->SetFrame(false);
        $graph->xgrid->Show();
        $graph->xaxis->SetTickLabels($dimensions);
        $graph->legend->SetPos(0.5, 0.93, 'center', 'bottom');
        $graph->SetTickDensity(TICKD_SPARSE);
        $graph->xaxis->SetFont(FF_DV_SANSSERIF);
        //$graph->xaxis->SetLabelAngle(10);

        $redPlot = new \LinePlot($redLine);
        $graph->Add($redPlot);

        $redPlot->SetLegend(Yii::t('dashboard', 'How I see me'));
        $redPlot->SetColor('red');
        $redPlot->SetFillColor('lightred@0.5');
        $redPlot->SetWeight(3);
        $redPlot->SetCenter();

        $bluePlot = new \LinePlot($blueLine);
        $graph->Add($bluePlot);
        $bluePlot->SetLegend(Yii::t('dashboard', 'How they see me'));
        $bluePlot->SetColor('blue');
        $bluePlot->SetFillColor('lightblue@0.5');
        $bluePlot->SetWeight(3);
        $bluePlot->SetCenter();

        $graph->Stroke();
    }

    public function actionMatrix($assessmentId, $memberId, $wheelType)
    {
        $performanceMatrix = Wheel::getPerformanceMatrix($assessmentId, $wheelType);

        if ($wheelType == Wheel::TYPE_GROUP)
            $title = Yii::t('dashboard', 'Group Performance Matrix');
        else if ($wheelType == Wheel::TYPE_ORGANIZATIONAL)
            $title = Yii::t('dashboard', 'Organizational Performance Matrix');
        else
            $title = Yii::t('dashboard', 'Individual Performance Matrix');

        $member = Person::findOne(['id' => $memberId]);

        if (!empty($member)) {
            $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
        } else {
            $title .= ' ' . Yii::t('app', 'of the team');
        }

        $width = 900;
        $height = 400;
        $topMargin = 30;
        $bottomMargin = 26;
        $horizontalMargin = 105;
        $matrixData = [];
        $minx = $horizontalMargin;
        $minProductivity = 10000;
        $maxx = $width - $horizontalMargin;
        $maxProductivity = -10000;
        $maxConsciousness = -100000;
        $current_value = 0;
        $sumConsciousness = 0;
        $sumProductivity = 0;
        $sumDeltaProductivity = 0;
        foreach ($performanceMatrix as $data) {
            if ($data['productivity'] < $minProductivity) {
                $minProductivity = $data['productivity'];
            }
            if ($data['productivity'] > $maxProductivity) {
                $maxProductivity = $data['productivity'];
            }
            if (abs($data['consciousness']) > $maxConsciousness) {
                $maxConsciousness = abs($data['consciousness']);
            }
            $sumConsciousness += abs($data['consciousness']);
            $sumProductivity += $data['productivity'];
        }

        $avgConsciousness = $sumConsciousness / count($performanceMatrix);
        $avgProductivity = $sumProductivity / count($performanceMatrix);
        $deltax = $maxx - $minx;
        $deltaProductivity = $maxProductivity - $minProductivity;
        foreach ($performanceMatrix as $data) {
            $current_value = $data['productivity'];
            $sumDeltaProductivity += pow($current_value - $avgProductivity, 2);
        }
        $avgDeltaProductivity = sqrt($sumDeltaProductivity / count($performanceMatrix)); //standar deviation

        $maxy = (floor(($maxConsciousness + 1) / 10) + 1.1) * 10;

        $m = ($topMargin + $bottomMargin - $height) / 2 / $maxy;
        foreach ($performanceMatrix as $data) {
            $posx = floor(($data['productivity'] - $minProductivity) / $deltaProductivity * $deltax + $minx);
            $posy = floor(($data['consciousness'] - $maxy) * $m) + $topMargin;
            $matrixData[] = [
                'name' => $data['name'],
                'x' => $posx,
                'y' => $posy,
                'id' => $data['id'],
                'consciousness' => $data['consciousness'],
                'productivity' => $data['productivity'],
            ];
        }

        $goodConsciousnessY1 = floor(($avgConsciousness - $maxy) * $m) + $topMargin;
        $goodConsciousnessY2 = floor((- $avgConsciousness - $maxy) * $m) + $topMargin;
        $goodConsciousnessYAxe = floor(($height - $topMargin - $bottomMargin) / 2) + $topMargin;
        $goodProductivityX1 = ($avgProductivity - $avgDeltaProductivity - $minProductivity) / $deltaProductivity * $deltax + $minx;
        $goodProductivityX2 = ($avgProductivity + $avgDeltaProductivity - $minProductivity) / $deltaProductivity * $deltax + $minx;

        // Lets draw

        require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvas.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_canvtools.php");

        // Setup a basic canvas we can work
        $g = new \CanvasGraph($width, $height);
        $g->SetFrame(false);

        // We need to stroke the plotarea and margin before we add the
        // text since we otherwise would overwrite the text.
        $g->InitFrame();

        // Create a new scale
        $scale = new \CanvasScale($g);
        $scale->Set(0, $goodConsciousnessY1, $width, $height);

        //high conciouness zone
        $g->img->SetColor('#d9edf7');
        $g->img->FilledRectangle(-1, $goodConsciousnessY1, $width, $goodConsciousnessY2);

        $g->img->SetColor('#5a9bbc');
        $g->img->Rectangle(-1, $goodConsciousnessY1, $width, $goodConsciousnessY2);

        // texts

        $t = new \Text($title, $width / 2, 5);
        $t->SetFont(FF_DV_SANSSERIF, FS_BOLD, 14);
        $t->Align('center', 'top');
        $t->SetColor("black");
        $t->Stroke($g->img);

        $t = new \Text('BP/BC+', 5, 5 + $topMargin);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 12);
        $t->Align('left', 'top');
        $t->SetColor("red");
        $t->Stroke($g->img);

        $t = new \Text('BP/AC', 5, $goodConsciousnessYAxe - 5);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 12);
        $t->Align('left', 'bottom');
        $t->SetColor("red");
        $t->Stroke($g->img);

        $t = new \Text('BP/BC-', 5, $height - $bottomMargin - 5);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 12);
        $t->Align('left', 'bottom');
        $t->SetColor("red");
        $t->Stroke($g->img);

        $t = new \Text('AP/BC+', $width - 5, 5 + $topMargin);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 12);
        $t->Align('right', 'top');
        $t->SetColor("red");
        $t->Stroke($g->img);

        $t = new \Text('AP/AC', $width - 5, $goodConsciousnessYAxe - 5);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 12);
        $t->Align('right', 'bottom');
        $t->SetColor("red");
        $t->Stroke($g->img);

        $t = new \Text('AP/BC-', $width - 5, $height - $bottomMargin - 5);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 12);
        $t->Align('right', 'bottom');
        $t->SetColor("red");
        $t->Stroke($g->img);

        //axes
        $posx = ($avgProductivity - $minProductivity) / $deltaProductivity * $deltax + $minx;
        $g->img->SetColor('#5a9bbc');
        $g->img->SetLineWeight(2);
        $g->img->Line(0, $goodConsciousnessYAxe, $width, $goodConsciousnessYAxe);
        $g->img->Line($posx, $topMargin, $posx, $height - $bottomMargin);
        $g->img->Line($minx, $topMargin, $minx, $height - $bottomMargin);
        $g->img->Line($maxx, $topMargin, $maxx, $height - $bottomMargin);

        //high productivity zone
        $g->img->SetLineWeight(1);
        $g->img->Line($goodProductivityX1, $topMargin, $goodProductivityX1, $height - $bottomMargin);
        $g->img->Line($goodProductivityX2, $topMargin, $goodProductivityX2, $height - $bottomMargin);

        //axes values
        $t = new \Text('Min: ' . round($minProductivity * 10) / 10 . ' %', $minx, $height - 6);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 10);
        $t->Align('center', 'bottom');
        $t->SetColor("red");
        $t->Stroke($g->img);

        $t = new \Text('Max: ' . round($maxProductivity * 10) / 10 . ' %', $maxx, $height - 6);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 10);
        $t->Align('center', 'bottom');
        $t->SetColor("red");
        $t->Stroke($g->img);

        $t = new \Text('Prom: ' . round($avgProductivity * 10) / 10 . ' %', $posx, $height - 6);
        $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 10);
        $t->Align('center', 'bottom');
        $t->SetColor("red");
        $t->Stroke($g->img);

        foreach ($matrixData as $data) {
            $highConsciousness = abs($data['consciousness']) < $avgConsciousness;

            $g->img->SetColor('#496987');
            $g->img->FilledCircle($data['x'], $data['y'], ($data['id'] == $memberId ? 15 : 11));

            if ($highConsciousness) {
                $g->img->SetColor('#5cb85c');
            } else {
                $g->img->SetColor('#f0ad4e');
            }

            $g->img->FilledCircle($data['x'], $data['y'], 10);


            $t = new \Text($data['name'], $data['x'], $data['y'] + 16);
            if ($data['id'] == $memberId) {
                $t->SetFont(FF_DV_SANSSERIF, FS_BOLD, 10);
            } else {
                $t->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 10);
            }
            $t->Align('center', 'top');
            $t->SetColor("#496987");
            $t->Stroke($g->img);
        }

        // Stroke the graph
        $g->Stroke();
        exit();
    }

}
