<?php

use yii\helpers\Html;
use yii\helpers\Url;
use sibilino\y2dygraphs\DygraphsWidget;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = Yii::t('wheel', 'Wheels');
$this->params['breadcrumbs'][] = $this->title;
?>


<script src="<?= yii\helpers\Url::to('@web/js/Chart.min.js') ?>"></script>
<div class="wheel-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($model->coachName) ?><br />
        <?= Yii::t('user', 'Client') ?>: <?= Html::label($model->coacheeName) ?>
    </p>
    <div class="col-md-2 col-xs-4">
        <?php
        foreach ($wheels as $wheelId => $wheelDate) {
            if ($wheelId == $id)
                echo $wheelDate . ' &LT;';
            else
                echo Html::a($wheelDate, Url::to(['index', 'wheelid' => $wheelId]));
            echo '<br />';
        }
        ?>
    </div>

    <div class="col-md-2 col-md-push-8 " style="text-align: right; color: red">
        <?php
        if ($compareId == -1)
            echo '&GT; ';
        echo Html::a('Ninguno', Url::to(['index', 'compareid' => -1]));
        echo '<br />';
        foreach ($wheels as $wheelId => $wheelDate) {
            if ($wheelId == $compareId)
                echo '> ' . $wheelDate;
            else
                echo Html::a($wheelDate, Url::to(['index', 'compareid' => $wheelId]), ['style' => 'color: red']);
            echo '<br />';
        }
        ?>
    </div>

    <div class="col-md-pull-1 col-md-6 " >
        <canvas id="canvas" height="350" width="350" class="img-responsive"></canvas>
    </div>


    <script>
        var radarChartData = {
        labels: [<?= '"' . implode('", "', $dimensions) . '"' ?>],
                datasets: [
<?php if ($compareId > 0) { ?>
            {
            label: "Actual",
                    fillColor: "rgba(255,0,0,0.2)",
                    strokeColor: "rgba(255,0,0,1)",
                    pointColor: "rgba(255,0,0,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [<?= '"' . implode('", "', $compare->dimensionAnswers) . '"' ?>]
            },
<?php } ?>
        {
        label: "Actual",
                fillColor: "rgba(0,0,255,0.2)",
                strokeColor: "rgba(0,0,255,1)",
                pointColor: "rgba(0,0,255,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?= '"' . implode('", "', $model->dimensionAnswers) . '"' ?>]
        },
        ]
        };
                window.onload = function() {
        window.myRadar = new Chart(document.getElementById("canvas").getContext("2d")).Radar(radarChartData, {
        responsive: true
        });
        }
    </script>
    <div class="clearfix"></div>
    <div class="col-sm-12 " style ="text-align: center;">
        <?= Html::a('Ver respuestas', Url::to(['wheel/form', 'Id' => $wheelId]), ['class' => 'btn btn-primary']) ?>
        <?= $compareId < 0 ? '' : Html::a('Ver respuestas', Url::to(['wheel/form', 'Id' => $compareId]), ['class' => 'btn btn-danger']) ?>
    </div>
    <br />
    <div class="col-sm-12 " style ="text-align: center;">
        <?= Html::a('Nueva rueda', Url::to(['wheel/form']), ['class' => 'btn btn-success']) ?>
    </div>
</div>
