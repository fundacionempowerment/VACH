<?php

use yii\helpers\Html;
use yii\helpers\Url;
use sibilino\y2dygraphs\DygraphsWidget;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = Yii::t('wheel', 'Wheel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = ['label' => $model->coachee->fullname, 'url' => ['/coachee/view', 'id' => $model->coachee->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<script src="<?= yii\helpers\Url::to('@web/js/Chart.min.js') ?>"></script>
<div class="wheel-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-4">
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($model->coach->fullname) ?><br />
            <?= Yii::t('user', 'Coachee') ?>: <?= Html::label($model->coachee->fullname) ?><br />
            <?= Yii::t('wheel', 'Date') ?>: <?= Html::label($model->date) ?><br />
        </p>
        <p>
            <?= Html::a('Ver respuestas', Url::to(['wheel/form', 'Id' => $model->id]), ['class' => 'btn btn-primary']) ?>
        </p>
        <p>
            <?= Yii::t('wheel', 'Compare to') ?>:<br />
            <?php
            if ($compare->id == 0)
                echo '&GT; ';
            echo Html::a('Ninguno', Url::to(['index', 'compareid' => -1]));
            echo '<br />';
            foreach ($wheels as $wheel) {
                if ($wheel['id'] == $compare->id) {
                    echo '> ' . $wheel['date'];
                    echo '<br />';
                } else if ($wheel['id'] != $model->id) {
                    echo Html::a($wheel['date'], Url::to(['index', 'compareid' => $wheel['id']]), ['style' => 'color: red']);
                    echo '<br />';
                }
            }
            ?>
        </p>
        <p>
            <br /><br />
            <?= Html::a('Nueva rueda', Url::to(['wheel/form']), ['class' => 'btn btn-success']) ?>
        </p>    </div>

    <div class="col-md-push-1 col-md-4" >
        <canvas id="canvas" height="350" width="350" class="img-responsive"></canvas>
    </div>

    <script>
        var radarChartData = {
        labels: [<?= '"' . implode('", "', $dimensions) . '"' ?>],
                datasets: [
<?php if ($compare->id > 0) { ?>
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
</div>
