<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$dimensions = [
    'Tiempo libre',
    'Trabajo',
    'Familia',
    'Dimensión física',
    'Dimensión emocional',
    'Dimensión mental',
    'Dimensión existencial',
    'Dimensión espiritual',
];
?>

<script src="<?= yii\helpers\Url::to('@web/js/Chart.min.js') ?>"></script>
<div class="wheel-view">
    <h3><?= Yii::t('wheel', 'Individual Wheel') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
        <canvas id="IndividualCanvas" height="350" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('wheel', 'Individual Group Projection Wheel') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
        <canvas id="IndividualGroupProjectionCanvas" height="350" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('wheel', 'Individual Organizational Projection Wheel') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
        <canvas id="IndividualOrganizationalProjectionCanvas" height="350" width="350" class="img-responsive"></canvas>
    </div>
    <script>
        var individualData = {
            labels: [<?= '"' . implode('", "', $dimensions) . '"' ?>],
            datasets: [
                {
                    label: "Individual",
                    fillColor: "rgba(0,0,255,0.2)",
                    strokeColor: "rgba(0,0,255,1)",
                    pointColor: "rgba(0,0,255,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $individualWheel) . '"' ?>]
                },
            ]
        };
        var individualGroupProjectionData = {
            labels: [<?= '"' . implode('", "', $dimensions) . '"' ?>],
            datasets: [
                {
                    label: "Group",
                    fillColor: "rgba(255,0,0,0.2)",
                    strokeColor: "rgba(255,0,0,1)",
                    pointColor: "rgba(255,0,0,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $projectedGroupWheel) . '"' ?>]
                },
                {
                    label: "Individual",
                    fillColor: "rgba(0,0,255,0.2)",
                    strokeColor: "rgba(0,0,255,1)",
                    pointColor: "rgba(0,0,255,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $individualWheel) . '"' ?>]
                },
            ]
        };
        var individualOrganizationalProjectionData = {
            labels: [<?= '"' . implode('", "', $dimensions) . '"' ?>],
            datasets: [
                {
                    label: "Group",
                    fillColor: "rgba(255,0,0,0.2)",
                    strokeColor: "rgba(255,0,0,1)",
                    pointColor: "rgba(255,0,0,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $projectedOrganizationalWheel) . '"' ?>]
                },
                {
                    label: "Individual",
                    fillColor: "rgba(0,0,255,0.2)",
                    strokeColor: "rgba(0,0,255,1)",
                    pointColor: "rgba(0,0,255,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $individualWheel) . '"' ?>]
                },
            ]
        };
        window.onload = function() {
            window.individualRadar = new Chart(document.getElementById("IndividualCanvas").getContext("2d")).Radar(individualData, {responsive: true});
            window.groupRadar = new Chart(document.getElementById("IndividualGroupProjectionCanvas").getContext("2d")).Radar(individualGroupProjectionData, {responsive: true});
            window.organizationalRadar = new Chart(document.getElementById("IndividualOrganizationalProjectionCanvas").getContext("2d")).Radar(individualOrganizationalProjectionData, {responsive: true});
        }
    </script>
</div>
