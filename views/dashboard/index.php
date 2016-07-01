<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if (!empty($assessment) && $assessment->version == 2) {
    $version = 2;
} else {
    $version = 1;
}

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
    var forwardRelationsData = new Array();
    var backwardRelationsData = new Array();
</script>
<div class="dashboard">
    <script src="<?= Url::to('@web/js/Chart.min.js') ?>"></script>
    <script src="<?= Url::to("@web/js/matrix.v$version.js") ?>"></script>
    <script src="<?= Url::to('@web/js/relations.js') ?>"></script>
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
            'member' => $member,
        ]);

    if (count($projectedIndividualWheel) > 0 && count($projectedGroupWheel) > 0)
        echo $this->render('_radar', [
            'title' => Yii::t('dashboard', 'Individual projection toward the group'),
            'wheel' => $projectedIndividualWheel,
            'wheelName' => Yii::t('dashboard', 'How I see me'),
            'comparedWheel' => $projectedGroupWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How they see me'),
            'type' => Wheel::TYPE_GROUP,
            'member' => $member,
        ]);

    if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0)
        echo $this->render('_lineal', [
            'title' => Yii::t('dashboard', 'Group Perception Matrix'),
            'wheel' => $reflectedGroupWheel,
            'wheelName' => Yii::t('dashboard', 'How they see me'),
            'comparedWheel' => $projectedGroupWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
            'type' => Wheel::TYPE_GROUP,
            'member' => $member,
        ]);

    if (count($projectedIndividualWheel) > 0 && count($projectedOrganizationalWheel) > 0)
        echo $this->render('_radar', [
            'title' => Yii::t('dashboard', 'Individual projection toward the organization'),
            'wheel' => $projectedIndividualWheel,
            'wheelName' => Yii::t('dashboard', 'How I see me'),
            'comparedWheel' => $projectedOrganizationalWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How they see me'),
            'type' => Wheel::TYPE_ORGANIZATIONAL,
            'member' => $member,
        ]);

    if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0)
        echo $this->render('_lineal', [
            'title' => Yii::t('dashboard', 'Organizational Perception Matrix'),
            'wheel' => $reflectedOrganizationalWheel,
            'wheelName' => Yii::t('dashboard', 'How they see me'),
            'comparedWheel' => $projectedOrganizationalWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
            'type' => Wheel::TYPE_ORGANIZATIONAL,
            'member' => $member,
        ]);

    if (count($gauges) > 0)
        echo $this->render('_gauges', [
            'gauges' => $gauges,
            'type' => $filter->wheelType,
            'member' => $member,
        ]);


    if (count($relationsMatrix) > 0) {
        echo $this->render('_number_matrix', [
            'data' => $relationsMatrix,
            'members' => $members,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
            'member' => $member,
            'assessment' => $assessment,
        ]);
    }

    if (count($performanceMatrix) > 0) {
        echo $this->render('_matrix', [
            'data' => $performanceMatrix,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
            'member' => $member,
        ]);
    }

    if (count($relationsMatrix) > 0) {
        echo $this->render('_relation', [
            'data' => $relationsMatrix,
            'members' => $members,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
            'member' => $member,
        ]);
        echo $this->render('_relation_table', [
            'data' => $relationsMatrix,
            'members' => $members,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
            'member' => $member,
        ]);
    }

    if (count($emergents))
        echo $this->render('_emergents', [
            'data' => $relationsMatrix,
            'members' => $members,
            'memberId' => $filter->memberId,
            'emergents' => $emergents,
            'type' => $filter->wheelType,
            'member' => $member,
        ]);
        echo $this->render('_detailed_emergents', [
            'data' => $relationsMatrix,
            'members' => $members,
            'memberId' => $filter->memberId,
            'emergents' => $emergents,
            'type' => $filter->wheelType,
            'member' => $member,
        ]);
    ?>
</div>
<script>
    window.onload = function () {
        for (var i in radars) {
            new Chart(document.getElementById("canvas" + radars[i]).getContext("2d")).Radar(radarsData[i], {responsive: true, scaleBeginAtZero: true, pointLabelFontSize: 15, scaleOverride: true, scaleSteps: 4, scaleStepWidth: 1, scaleStartValue: 0});
        }
        for (var i in lineals) {
            new Chart(document.getElementById("canvas" + lineals[i]).getContext("2d")).Line(linealsData[i], {responsive: true, scaleBeginAtZero: true, scaleFontSize: 15, scaleOverride: true, scaleSteps: 4, scaleStepWidth: 1, scaleStartValue: 0, bezierCurve: false});
        }
        for (var i in matrixes) {
            doMatrix(document.getElementById("canvas" + matrixes[i] + 'r').getContext("2d"), matrixesData[i], false);
        }
        for (var i in relations) {
            doForwardRelations(document.getElementById("canvas" + relations[i] + 'f').getContext("2d"), forwardRelationsData[i]);
            doBackwardRelations(document.getElementById("canvas" + relations[i] + 'b').getContext("2d"), backwardRelationsData[i]);
        }
    }
</script>
<script src="<?= Url::to('@web/js/html2canvas/html2canvas.js') ?>"></script>
<script src="<?= Url::to('@web/js/FileSaver.js') ?>"></script>
<script>
    function printDiv(div)
    {
        html2canvas(document.querySelector("#" + div)).then(function (canvas) {
            canvas.toBlob(function (blob) {
                saveAs(blob, "image.png");
            }, "image/png");
        });

    }
</script>
