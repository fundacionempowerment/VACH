<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$individualDimensions = WheelQuestion::getDimensionNames(Wheel::TYPE_INDIVIDUAL);
$groupDimensions = WheelQuestion::getDimensionNames(Wheel::TYPE_GROUP);
$organizationalDimensions = WheelQuestion::getDimensionNames(Wheel::TYPE_ORGANIZATIONAL);

$data = [
    [67.59, -12.44],
    [77.78, -9.34],
    [79.34, -10.53]
];

$data = [
    [60, 40],
    [140, 76],
    [176, 80]
];
?>

<script src="<?= yii\helpers\Url::to('@web/js/Chart.min.js') ?>"></script>
<div class="wheel-view">
    <h3><?= Yii::t('dashboard', 'Individual Wheel') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
        <canvas id="IndividualCanvas" height="350" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('dashboard', 'Performance Matrix') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
        <canvas id="PerformanceMatrix" height="200" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('dashboard', 'Individual projection toward the group') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
        <canvas id="IndividualGroupProjectionCanvas" height="350" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('dashboard', 'Group Perception Matrix') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-2 col-md-8" >
        <canvas id="IndividualGroupMatrixCanvas" height="150" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('dashboard', 'Individual projection toward the organization') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
        <canvas id="IndividualOrganizationalProjectionCanvas" height="350" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('dashboard', 'Organizational Perception Matrix') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-2 col-md-8" >
        <canvas id="IndividualOrganizationalMatrixCanvas" height="150" width="350" class="img-responsive"></canvas>
    </div>
    <div class="clearfix"></div>
    <h3><?= Yii::t('dashboard', 'Potential Matrix') ?></h3>
    <div class="col-xs-push-2 col-xs-8 col-md-push-2 col-md-8" >
        <?=
        Progress::widget([
            'percent' => 60,
            'label' => 'test',
        ]);
        ?>
    </div>

    <script>
        var individualData = {
            labels: [<?= '"' . implode('", "', $individualDimensions) . '"' ?>],
            datasets: [
                {
                    label: "Individual",
                    fillColor: "rgba(0,0,255,0.2)",
                    strokeColor: "rgba(0,0,255,1)",
                    pointColor: "rgba(0,0,255,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $projectedIndividualWheel) . '"' ?>]
                },
            ]
        };
        var individualGroupProjectionData = {
            labels: [<?= '"' . implode('", "', $groupDimensions) . '"' ?>],
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
                    data: [<?= '"' . implode('", "', $projectedIndividualWheel) . '"' ?>]
                },
            ]
        };
        var individualOrganizationalProjectionData = {
            labels: [<?= '"' . implode('", "', $organizationalDimensions) . '"' ?>],
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
                    data: [<?= '"' . implode('", "', $projectedIndividualWheel) . '"' ?>]
                },
            ]
        };
        var individualGroupMatrixData = {
            labels: [<?= '"' . implode('", "', $groupDimensions) . '"' ?>],
            datasets: [
                {
                    label: "Group",
                    fillColor: "rgba(255,0,0,0.2)",
                    strokeColor: "rgba(255,0,0,1)",
                    pointColor: "rgba(255,0,0,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $reflectedGroupWheel) . '"' ?>]
                },
                {
                    label: "Individual",
                    fillColor: "rgba(0,0,255,0.2)",
                    strokeColor: "rgba(0,0,255,1)",
                    pointColor: "rgba(0,0,255,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $projectedGroupWheel) . '"' ?>]
                },
            ]
        };
        var individualOrganizationalMatrixData = {
            labels: [<?= '"' . implode('", "', $organizationalDimensions) . '"' ?>],
            datasets: [
                {
                    label: "Group",
                    fillColor: "rgba(255,0,0,0.2)",
                    strokeColor: "rgba(255,0,0,1)",
                    pointColor: "rgba(255,0,0,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $reflectedOrganizationalWheel) . '"' ?>]
                },
                {
                    label: "Individual",
                    fillColor: "rgba(0,0,255,0.2)",
                    strokeColor: "rgba(0,0,255,1)",
                    pointColor: "rgba(0,0,255,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $projectedOrganizationalWheel) . '"' ?>]
                },
            ]
        };
        window.onload = function() {
            window.individualRadar = new Chart(document.getElementById("IndividualCanvas").getContext("2d")).Radar(individualData, {responsive: true});
            window.groupRadar = new Chart(document.getElementById("IndividualGroupProjectionCanvas").getContext("2d")).Radar(individualGroupProjectionData, {responsive: true});
            window.organizationalRadar = new Chart(document.getElementById("IndividualOrganizationalProjectionCanvas").getContext("2d")).Radar(individualOrganizationalProjectionData, {responsive: true});
            window.groupMatrix = new Chart(document.getElementById("IndividualGroupMatrixCanvas").getContext("2d")).Line(individualGroupProjectionData, {responsive: true});
            window.organizationalRadar = new Chart(document.getElementById("IndividualOrganizationalMatrixCanvas").getContext("2d")).Line(individualOrganizationalMatrixData, {responsive: true});

            var canvas = document.getElementById("PerformanceMatrix");
            var context = canvas.getContext("2d");

            // do cool things with the context
            context.fillStyle = 'red';
            context.fillText('BC/BP+', 10, 10);
            context.fillText('BC/BP-', 10, 190);
            context.fillText('BC/AP+', 310, 10);
            context.fillText('BC/AP-', 310, 190);

            //frame
            context.beginPath();
            context.moveTo(0, 0);
            context.lineTo(0, 200);
            context.lineTo(350, 200);
            context.lineTo(350, 0);
            context.lineTo(0, 0);
            context.stroke();

            //high conciouness zone
            context.beginPath();
            context.rect(0, 100 - 10, 350, 20);
            context.fillStyle = '#d9edf7';
            context.fill();
            context.strokeStyle = '#5a9bbc';
            context.stroke();

            //axes
            context.strokeStyle = '#5a9bbc';
            context.beginPath();
            context.moveTo(0, 100);
            context.lineTo(350, 100);
            context.moveTo(175, 0);
            context.lineTo(175, 200);
            context.stroke();

            //persons
<?php foreach ($data as $person) { ?>
                context.beginPath();
                context.arc(<?= $person[0] ?>, <?= $person[1] ?>, 15, 0, 2 * Math.PI, false);
                context.fillStyle = '#eea8a8';
                context.fill();
                context.strokeStyle = '#5a9bbc';
                context.stroke();

                context.fillStyle = '#496987';
                context.textAlign = 'center';
                context.fillText('Person', <?= $person[0] ?>, <?= $person[1] + 25 ?>);
<?php } ?>
        }
    </script>
</div>
