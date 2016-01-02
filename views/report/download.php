<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use yii\bootstrap\Button;
use app\models\Wheel;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('report', 'Report');
?>
<script>
    var radars = new Array();
    var radarsData = new Array();
    var lineals = new Array();
    var linealsData = new Array();
    var matrixes = new Array();
    var matrixesData = new Array();
    var relations = new Array();
    var relationsData = new Array();
</script>
<script src="<?= Url::to('@web/js/Chart.min.js') ?>"></script>
<script src="<?= Url::to('@web/js/matrix.js') ?>"></script>
<script src="<?= Url::to('@web/js/relations.js') ?>"></script>
<div class="report-technical row">
    <div class="jumbotron">
        <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
        <h2>
            <?= Html::label($assessment->team->company->name) ?>
            <?= Html::label($assessment->team->name) ?>
        </h2>
        <h3>
            <?= Yii::t('report', 'Report') ?>
        </h3>
        <h3>
            <?= Yii::$app->formatter->asDate('now') ?>
        </h3>
    </div>
    <div class="col-lg-12">
        <h3>
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($assessment->team->company->name) ?>
            <br/>
            <?= Yii::t('team', 'Team') ?>: <?= Html::label($assessment->team->name) ?>
            <br/>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?>
            <br/>
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($assessment->team->sponsor->fullname) ?>
            <br/>
            <?= Yii::t('team', 'Members') ?>:
            <ul>
                <?php foreach ($assessment->team->members as $teamMember) { ?>
                    <li>
                        <?= Html::label($teamMember->member->fullname) ?>
                    </li>
                <?php } ?>
            </ul>
        </h3>
    </div>
    <div class="col-lg-12">
        <h2>
            <?= Yii::t('report', 'Index') ?>
        </h2>
        <h3>
            <ol>
                <li><?= Yii::t('report', 'Introduction') ?></li>
                <li><?= Yii::t('report', 'Fundaments') ?></li>
                <li><?= Yii::t('report', 'Group and Organizational Analysis') ?></li>
                <li><?= Yii::t('report', 'Individual Analysis') ?></li>
                <li><?= Yii::t('report', 'Summary') ?></li>
                <li><?= Yii::t('report', 'Action Plan') ?></li>
            </ol>
        </h3>
    </div>
    <div class="col-lg-12">
        <h2>
            1. <?= Yii::t('report', 'Introduction') ?>
        </h2>
        <div id="view-introduction">
            <?= $assessment->report->introduction ?>
        </div>        
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        <?= $this->render('_fundaments', []) ?>
    </div>
    <div class="col-lg-12">
        <h2>
            3. <?= Yii::t('report', 'Group and Organizational Analysis') ?>
        </h2>
        <?=
        $this->render('_effectiveness', [
            'assessment' => $assessment,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_performance', [
            'assessment' => $assessment,
            'groupPerformanceMatrix' => $groupPerformanceMatrix,
            'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_relations', [
            'assessment' => $assessment,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_competences', [
            'assessment' => $assessment,
            'groupGauges' => $groupGauges,
            'organizationalGauges' => $organizationalGauges,
            'members' => $members,
        ])
        ?>
        <?=
        $this->render('_emergents', [
            'assessment' => $assessment,
            'groupEmergents' => $groupEmergents,
            'organizationalEmergents' => $organizationalEmergents,
            'members' => $members,
        ])
        ?>
    </div>
    <h2>
        4. <?= Yii::t('report', 'Individual Analysis') ?>
    </h2>
    <div class="col-lg-12">
        <?php
        foreach ($assessment->report->individualReports as $individualReport) {
            $projectedGroupWheel = Wheel::getProjectedGroupWheel($assessment->id, $individualReport->user_id);
            $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($assessment->id, $individualReport->user_id);
            $reflectedGroupWheel = Wheel::getReflectedGroupWheel($assessment->id, $individualReport->user_id);
            $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($assessment->id, $individualReport->user_id);
            $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
            $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);
            $groupGauges = Wheel::getMemberGauges($assessment->id, $individualReport->user_id, Wheel::TYPE_GROUP);
            $organizationalGauges = Wheel::getMemberGauges($assessment->id, $individualReport->user_id, Wheel::TYPE_ORGANIZATIONAL);
            $groupEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->user_id, Wheel::TYPE_GROUP);
            $organizationalEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->user_id, Wheel::TYPE_ORGANIZATIONAL);
            $subtitle_number = 97; // letter 'a'
            ?>
            <h1>
                <?= $individualReport->member->fullname ?>
            </h1>
            <?php
            if ($individualReport->performance != '') {
                echo $this->render('_individual_performance', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupPerformanceMatrix' => $groupPerformanceMatrix,
                    'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                    'subtitle_letter' => chr($subtitle_number),
                ]);
                $subtitle_number++;
            }
            echo $this->render('_individual_perception', [
                'report' => $individualReport,
                'assessment' => $assessment,
                'projectedGroupWheel' => $projectedGroupWheel,
                'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                'reflectedGroupWheel' => $reflectedGroupWheel,
                'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
                'subtitle_letter' => chr($subtitle_number),
            ]);
            $subtitle_number++;
            echo $this->render('_individual_relations', [
                'report' => $individualReport,
                'assessment' => $assessment,
                'groupRelationsMatrix' => $groupRelationsMatrix,
                'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                'members' => $members,
                'subtitle_letter' => chr($subtitle_number),
            ]);
            $subtitle_number++;
            echo $this->render('_individual_competences', [
                'report' => $individualReport,
                'assessment' => $assessment,
                'groupGauges' => $groupGauges,
                'organizationalGauges' => $organizationalGauges,
                'members' => $members,
                'subtitle_letter' => chr($subtitle_number),
            ]);
            $subtitle_number++;
            echo $this->render('_individual_emergents', [
                'report' => $individualReport,
                'assessment' => $assessment,
                'groupEmergents' => $groupEmergents,
                'organizationalEmergents' => $organizationalEmergents,
                'members' => $members,
                'subtitle_letter' => chr($subtitle_number),
            ]);
        }
        ?>
    </div>
    <div class="col-lg-12">
        <h2>
            5. <?= Yii::t('report', 'Summary') ?>
        </h2>
        <div id="view-introduction">
            <?= $assessment->report->summary ?>
        </div>  
    </div>
    <div class="col-lg-12">
        <h2>
            6. <?= Yii::t('report', 'Action Plan') ?>
        </h2>
        <div id="view-introduction">
            <?= $assessment->report->action_plan ?>
        </div>  
    </div>
</div>
<script>
    window.onload = function () {
        for (var i in radars) {
            new Chart(document.getElementById("canvas" + radars[i]).getContext("2d")).Radar(radarsData[i], {responsive: true, scaleBeginAtZero: true, pointLabelFontSize: 15, scaleOverride: true, scaleSteps: 4, scaleStepWidth: 1, scaleStartValue: 0, animation: false});
        }
        for (var i in lineals) {
            new Chart(document.getElementById("canvas" + lineals[i]).getContext("2d")).Line(linealsData[i], {responsive: true, scaleBeginAtZero: true, scaleFontSize: 15, scaleOverride: true, scaleSteps: 4, scaleStepWidth: 1, scaleStartValue: 0, bezierCurve: false, animation: false});
        }
        for (var i in matrixes) {
            doMatrix(document.getElementById("canvas" + matrixes[i]).getContext("2d"), matrixesData[i]);
        }
        for (var i in relations) {
            doRelations(document.getElementById("canvas" + relations[i]).getContext("2d"), relationsData[i]);
        }
    }
</script>
