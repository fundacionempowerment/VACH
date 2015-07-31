<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = Yii::t('dashboard', 'Dashboard');

$this->params['breadcrumbs'][] = $this->title;
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
<div class="dashboard">
    <script src="<?= yii\helpers\Url::to('@web/js/Chart.min.js') ?>"></script>
    <script src="<?= yii\helpers\Url::to('@web/js/matrix.js') ?>"></script>
    <script src="<?= yii\helpers\Url::to('@web/js/relations.js') ?>"></script>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    echo $this->render('_filter', [
        'filter' => $filter,
        'companies' => $companies,
        'teams' => $teams,
        'assessments' => $assessments,
        'members' => $members,
    ]);

    if (count($projectedIndividualWheel) > 0)
        echo $this->render('_radar', [
            'title' => Yii::t('dashboard', 'Individual Wheel'),
            'wheel' => $projectedIndividualWheel,
            'wheelName' => Yii::t('dashboard', 'How I see me'),
            'type' => Wheel::TYPE_INDIVIDUAL,
        ]);

    if (count($projectedIndividualWheel) > 0 && count($projectedGroupWheel) > 0)
        echo $this->render('_radar', [
            'title' => Yii::t('dashboard', 'Individual projection toward the group'),
            'wheel' => $projectedIndividualWheel,
            'wheelName' => Yii::t('dashboard', 'How I see me'),
            'comparedWheel' => $projectedGroupWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How they see me'),
            'type' => Wheel::TYPE_GROUP,
        ]);

    if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0)
        echo $this->render('_lineal', [
            'title' => Yii::t('dashboard', 'Group Perception Matrix'),
            'wheel' => $reflectedGroupWheel,
            'wheelName' => Yii::t('dashboard', 'How they see me'),
            'comparedWheel' => $projectedGroupWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
            'type' => Wheel::TYPE_GROUP,
        ]);

    if (count($projectedIndividualWheel) > 0 && count($projectedOrganizationalWheel) > 0)
        echo $this->render('_radar', [
            'title' => Yii::t('dashboard', 'Individual projection toward the organization'),
            'wheel' => $projectedIndividualWheel,
            'wheelName' => Yii::t('dashboard', 'How I see me'),
            'comparedWheel' => $projectedOrganizationalWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How they see me'),
            'type' => Wheel::TYPE_ORGANIZATIONAL,
        ]);

    if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0)
        echo $this->render('_lineal', [
            'title' => Yii::t('dashboard', 'Organizational Perception Matrix'),
            'wheel' => $reflectedOrganizationalWheel,
            'wheelName' => Yii::t('dashboard', 'How they see me'),
            'comparedWheel' => $projectedOrganizationalWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
            'type' => Wheel::TYPE_ORGANIZATIONAL,
        ]);

    // group
    if (count($gauges) > 0)
        echo $this->render('_gauges', [
            'wheel' => $gauges,
            'type' => $filter->wheelType,
        ]);

    if (count($performanceMatrix) > 0) {
        echo $this->render('_matrix', [
            'data' => $performanceMatrix,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
        ]);
    }

    if (count($relationsMatrix) > 0) {
        echo $this->render('_relation', [
            'data' => $relationsMatrix,
            'members' => $members,
            'memberRelationMatrix' => $memberRelationMatrix,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
        ]);

        echo $this->render('_number_matrix', [
            'data' => $relationsMatrix,
            'members' => $members,
            'memberRelationMatrix' => $memberRelationMatrix,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
        ]);
    }

    if (count($emergents))
        echo $this->render('_emergents', [
            'emergents' => $emergents,
            'type' => $filter->wheelType,
        ]);

    if (count($emergents))
        echo $this->render('_emergents_detailed', [
            'emergents' => $emergents,
            'type' => $filter->wheelType,
        ]);
    ?>
</div>
<script>

    window.onload = function() {
        for (var i in radars) {
            new Chart(document.getElementById("canvas" + radars[i]).getContext("2d")).Radar(radarsData[i], {responsive: true, scaleBeginAtZero: true, pointLabelFontSize: 15});
        }
        for (var i in lineals) {
            new Chart(document.getElementById("canvas" + lineals[i]).getContext("2d")).Line(linealsData[i], {responsive: true, scaleBeginAtZero: true, scaleFontSize: 15, bezierCurve: false});
        }
        for (var i in matrixes) {
            doMatrix(document.getElementById("canvas" + matrixes[i]).getContext("2d"), matrixesData[i]);
        }
        for (var i in relations) {
            doRelations(document.getElementById("canvas" + relations[i]).getContext("2d"), relationsData[i]);
        }
    }
</script>

