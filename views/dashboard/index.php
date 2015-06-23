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
            'wheelName' => Yii::t('dashboard', 'How I see myself'),
            'type' => Wheel::TYPE_INDIVIDUAL,
        ]);
//
//    if (count($individualPerformanceMatrix) > 0)
//        echo $this->render('_matrix', [
//            'title' => Yii::t('dashboard', 'Performance Matrix'),
//            'matrix' => $individualPerformanceMatrix,
//        ]);

    if (count($projectedIndividualWheel) > 0 && count($projectedGroupWheel) > 0)
        echo $this->render('_radar', [
            'title' => Yii::t('dashboard', 'Individual projection toward the group'),
            'wheel' => $projectedIndividualWheel,
            'wheelName' => Yii::t('dashboard', 'How I see myself'),
            'comparedWheel' => $projectedGroupWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How other see me'),
            'type' => Wheel::TYPE_GROUP,
        ]);

    if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0)
        echo $this->render('_lineal', [
            'title' => Yii::t('dashboard', 'Group Perception Matrix'),
            'wheel' => $reflectedGroupWheel,
            'wheelName' => Yii::t('dashboard', 'How other see me'),
            'comparedWheel' => $projectedGroupWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see myself'),
            'type' => Wheel::TYPE_GROUP,
        ]);

    if (count($projectedIndividualWheel) > 0 && count($projectedOrganizationalWheel) > 0)
        echo $this->render('_radar', [
            'title' => Yii::t('dashboard', 'Individual projection toward the group'),
            'wheel' => $projectedIndividualWheel,
            'wheelName' => Yii::t('dashboard', 'How I see myself'),
            'comparedWheel' => $projectedOrganizationalWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How other see me'),
            'type' => Wheel::TYPE_ORGANIZATIONAL,
        ]);

    if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0)
        echo $this->render('_lineal', [
            'title' => Yii::t('dashboard', 'Organizational Perception Matrix'),
            'wheel' => $reflectedOrganizationalWheel,
            'wheelName' => Yii::t('dashboard', 'How other see me'),
            'comparedWheel' => $projectedOrganizationalWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see myself'),
            'type' => Wheel::TYPE_GROUP,
        ]);

    if (count($individualEmergents))
        echo $this->render('_emergents', [
            'title' => Yii::t('dashboard', 'Individual Potencial Matrix'),
            'emergents' => $individualEmergents,
        ]);

    // group
    if (count($groupWheel) > 0)
        echo $this->render('_gauges', [
            'title' => Yii::t('dashboard', 'Group indicators'),
            'wheel' => $groupWheel,
            'type' => Wheel::TYPE_GROUP,
        ]);

    if (count($groupPerformanceMatrix) > 0)
        echo $this->render('_matrix', [
            'title' => Yii::t('dashboard', 'Group Potential Matrix'),
            'data' => $groupPerformanceMatrix,
            'type' => Wheel::TYPE_GROUP,
        ]);

    if (count($groupRelationsMatrix) > 0)
        echo $this->render('_relation', [
            'title' => Yii::t('dashboard', 'Group Relations Matrix'),
            'data' => $groupRelationsMatrix,
            'members' => $members,
            'memberRelationMatrix' => $memberRelationMatrix,
            'type' => Wheel::TYPE_GROUP,
            'memberId' => $filter->memberId,
        ]);

    if (count($groupRelationsMatrix) > 0)
        echo $this->render('_number_matrix', [
            'title' => Yii::t('dashboard', 'Group conciousness and responsability Matrix'),
            'data' => $groupRelationsMatrix,
            'members' => $members,
            'memberRelationMatrix' => $memberRelationMatrix,
            'type' => Wheel::TYPE_GROUP,
            'memberId' => $filter->memberId,
        ]);

    if (count($groupEmergents))
        echo $this->render('_emergents', [
            'title' => Yii::t('dashboard', 'Group Potencial Matrix'),
            'emergents' => $groupEmergents,
        ]);

    // organizational
    if (count($organizationalWheel) > 0)
        echo $this->render('_gauges', [
            'title' => Yii::t('dashboard', 'Organizational indicators'),
            'wheel' => $organizationalWheel,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
        ]);

    if (count($organizationalPerformanceMatrix) > 0)
        echo $this->render('_matrix', [
            'title' => Yii::t('dashboard', 'Organizational Potential Matrix'),
            'data' => $organizationalPerformanceMatrix,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
        ]);

    if (count($organizationalRelationsMatrix) > 0)
        echo $this->render('_relation', [
            'title' => Yii::t('dashboard', 'Organizational Relations Matrix'),
            'data' => $organizationalRelationsMatrix,
            'members' => $members,
            'memberRelationMatrix' => $memberRelationMatrix,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
            'memberId' => $filter->memberId,
        ]);

    if (count($organizationalRelationsMatrix) > 0)
        echo $this->render('_number_matrix', [
            'title' => Yii::t('dashboard', 'Organizational conciousness and responsability Matrix'),
            'data' => $organizationalRelationsMatrix,
            'members' => $members,
            'memberRelationMatrix' => $memberRelationMatrix,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
            'memberId' => $filter->memberId,
        ]);

    if (count($organizationalEmergents))
        echo $this->render('_emergents', [
            'title' => Yii::t('dashboard', 'Organizational Potencial Matrix'),
            'emergents' => $organizationalEmergents,
        ]);
    ?>
</div>
<script>

    window.onload = function() {
        for (var i in radars) {
            new Chart(document.getElementById("canvas" + radars[i]).getContext("2d")).Radar(radarsData[i], {responsive: true});
        }
        for (var i in lineals) {
            new Chart(document.getElementById("canvas" + lineals[i]).getContext("2d")).Line(linealsData[i], {responsive: true});
        }
        for (var i in matrixes) {
            doMatrix(document.getElementById("canvas" + matrixes[i]).getContext("2d"), matrixesData[i]);
        }
        for (var i in relations) {
            doRelations(document.getElementById("canvas" + relations[i]).getContext("2d"), relationsData[i]);
        }
    }
</script>

