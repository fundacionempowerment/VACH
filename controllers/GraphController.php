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

        \JpGraph\JpGraph::load();
        \JpGraph\JpGraph::module('radar');

        $graph = new \RadarGraph(700, 400);
        $graph->SetScale('lin', 0, 4);
        $graph->yscale->ticks->Set(4, 1);
        $graph->SetColor('white');
        $graph->frame_weight = 0;
        $graph->SetSize(0.65);
        $graph->SetCenter(0.5, 0.55);

        $graph->axis->SetFont(FF_DV_SANSSERIF, FS_BOLD);
        $graph->axis->SetWeight(1);

        // Uncomment the following lines to also show grid lines.
        $graph->grid->SetLineStyle('dashed');
        $graph->grid->SetColor('navy@0.5');
        $graph->grid->Show();

        $graph->ShowMinorTickMarks();
        $graph->title->Set($title);

        $graph->title->SetFont(FF_DV_SANSSERIF, FS_BOLD, 16);
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

}
