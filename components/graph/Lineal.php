<?php

namespace app\components\graph;

use Yii;
use app\models\Person;
use app\models\Wheel;
use app\models\WheelQuestion;

class Lineal
{

    static public function draw($assessmentId, $memberId, $wheelType)
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
        exit();
    }

}
