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

$radarDiameter = 350;
$linealWidth = $radarDiameter * 1.5;
$linealHeight = 200;

$matrixData = [];

$minProductivity = 10000;
$maxProductivity = -10000;
$maxConsciousness = -100000;

foreach ($performanceMatrix as $data) {
    if ($data['productivity'] < $minProductivity)
        $minProductivity = $data['productivity'];
    if ($data['productivity'] > $maxProductivity)
        $maxProductivity = $data['productivity'];
    if (abs($data['consciousness']) > $maxConsciousness)
        $maxConsciousness = abs($data['consciousness']);
}

$minx = intval(($minProductivity - 1) / 10) * 10;
$maxx = (intval(($maxProductivity + 1) / 10)) * 10;
$maxy = (intval(($maxConsciousness + 1) / 10) + 1) * 10;

foreach ($performanceMatrix as $data) {
    $posx = intval(($data['productivity'] - $minx) / ($maxx - $minx) * $linealWidth);
    $posy = intval(($maxy - $data['consciousness']) * $linealHeight / 2 / $maxy);
    $matrixData[] = [$data['name'], $posx, $posy];
}

$this->title = Yii::t('dashboard', 'Dashboard');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dashboard">
    <h1><?= Html::encode($this->title) ?></h1>
    <?=
    $this->render('_filter', [
        'filter' => $filter,
        'companies' => $companies,
        'teams' => $teams,
        'assessments' => $assessments,
        'members' => $members,
    ])
    ?>
    <script src="<?= yii\helpers\Url::to('@web/js/Chart.min.js') ?>"></script>
    <div class="wheel-view">
        <h3><?= Yii::t('dashboard', 'Individual Wheel') ?></h3>
        <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
            <canvas id="IndividualCanvas" height="<?= $radarDiameter ?>" width="<?= $radarDiameter ?>" class="img-responsive"></canvas>
        </div>
        <div class="clearfix"></div>
        <h3><?= Yii::t('dashboard', 'Performance Matrix') ?></h3>
        <div class="col-md-12" >
            <canvas id="PerformanceMatrix" height="<?= $linealHeight ?>" width="<?= $linealWidth ?>" class="img-responsive"></canvas>
        </div>
        <div class="clearfix"></div>
        <h3><?= Yii::t('dashboard', 'Individual projection toward the group') ?></h3>
        <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
            <canvas id="IndividualGroupProjectionCanvas" height="<?= $radarDiameter ?>" width="<?= $radarDiameter ?>" class="img-responsive"></canvas>
        </div>
        <div class="clearfix"></div>
        <h3><?= Yii::t('dashboard', 'Group Perception Matrix') ?></h3>
        <div class="col-xs-push-2 col-xs-8 col-md-push-2 col-md-8" >
            <canvas id="IndividualGroupMatrixCanvas" height="<?= $linealHeight ?>" width="<?= $linealWidth ?>" class="img-responsive"></canvas>
        </div>
        <div class="clearfix"></div>
        <h3><?= Yii::t('dashboard', 'Individual projection toward the organization') ?></h3>
        <div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
            <canvas id="IndividualOrganizationalProjectionCanvas" height="<?= $radarDiameter ?>" width="<?= $radarDiameter ?>" class="img-responsive"></canvas>
        </div>
        <div class="clearfix"></div>
        <h3><?= Yii::t('dashboard', 'Organizational Perception Matrix') ?></h3>
        <div class="col-xs-push-2 col-xs-8 col-md-push-2 col-md-8" >
            <canvas id="IndividualOrganizationalMatrixCanvas" height="<?= $linealHeight ?>" width="<?= $linealWidth ?>" class="img-responsive"></canvas>
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

                var context = document.getElementById("PerformanceMatrix").getContext("2d");
                var width = <?= $linealWidth ?>;
                var height = <?= $linealHeight ?>;

                // do cool things with the context
                context.fillStyle = 'red';
                context.textAlign = 'left';
                context.textBaseline = 'top';
                context.fillText('BC/BP+', 5, 5);
                context.textAlign = 'left';
                context.textBaseline = 'bottom';
                context.fillText('BC/BP-', 5, height - 5);
                context.textAlign = 'right';
                context.textBaseline = 'top';
                context.fillText('BC/AP+', width - 5, 5);
                context.textAlign = 'right';
                context.textBaseline = 'bottom';
                context.fillText('BC/AP-', width - 5, height - 5);

                //frame
                context.beginPath();
                context.moveTo(0, 0);
                context.lineTo(0, height);
                context.lineTo(width, height);
                context.lineTo(width, 0);
                context.lineTo(0, 0);
                context.stroke();

                //high conciouness zone
                context.beginPath();
                context.rect(0, height / 2 - 10, width, 20);
                context.fillStyle = '#d9edf7';
                context.fill();
                context.strokeStyle = '#5a9bbc';
                context.stroke();

                //axes
                context.strokeStyle = '#5a9bbc';
                context.beginPath();
                context.moveTo(0, height / 2);
                context.lineTo(width, height / 2);
                context.moveTo(width / 2, 0);
                context.lineTo(width / 2, height);
                context.stroke();

                //persons
<?php foreach ($matrixData as $person) { ?>
                    context.beginPath();
                    context.arc(<?= $person[1] ?>, <?= $person[2] ?>, 15, 0, 2 * Math.PI, false);
                    context.fillStyle = '#eea8a8';
                    context.fill();
                    context.strokeStyle = '#5a9bbc';
                    context.stroke();

                    context.fillStyle = '#496987';
                    context.textAlign = 'center';
                    context.fillText('<?= $person[0] ?>', <?= $person[1] ?>, <?= $person[2] + 25 ?>);
<?php } ?>
            }
        </script>
    </div>
</div>