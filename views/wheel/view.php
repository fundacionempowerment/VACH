<?php

use yii\helpers\Html;
use yii\helpers\Url;
use sibilino\y2dygraphs\DygraphsWidget;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = Yii::t('wheel', 'Wheel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'My Persons'), 'url' => ['/person']];
$this->params['breadcrumbs'][] = ['label' => $model->person->fullname, 'url' => ['/person/view', 'id' => $model->person->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<script src="<?= yii\helpers\Url::to('@web/js/Chart.min.js') ?>"></script>
<div class="wheel-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-4">
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($model->coach->fullname) ?><br />
            <?= Yii::t('user', 'Person') ?>: <?= Html::label($model->person->fullname) ?><br />
            <?= Yii::t('wheel', 'Date') ?>: <?= Html::label($model->date) ?><br />
        </p>
        <p>
            <?=
            count($model->answers) == 80 ?
                    Html::a(Yii::t('wheel', 'View answers'), Url::to(['wheel/answers', 'id' => $model->id]), ['class' => 'btn btn-primary']) :
                    Html::a(Yii::t('wheel', 'continue...'), Url::to(['wheel/run', 'person_id' => $model->person->id, 'id' => $model->id]), ['class' => 'btn btn-success'])
            ?>
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
                    echo Html::a($wheel['date'], Url::to(['index', 'compareid' => $wheel['id']]));
                    echo '<br />';
                }
            }
            ?>
        </p>
        <p>
            <br /><br />
            <?= Html::a(Yii::t('wheel', 'New wheel'), Url::to(['wheel/run', 'person_id' => $model->person->id]), ['class' => 'btn btn-success']) ?>
        </p>    </div>

    <div class="col-md-4" >
        <canvas id="canvas" height="350" width="350" class="img-responsive"></canvas>
    </div>
    <div class="col-md-push-1 col-md-3" style="text-align: right;">
        <br /><br />
        <?php
        foreach ($dimensions as $key => $text) {
            echo $text . ': ' . '<span style="color: blue;">' . $model->dimensionAnswers[$key] . '</span>';
            if ($compare->id > 0) {
                echo ' - <span style="color: red;">' . $compare->dimensionAnswers[$key] . '</span>' . ' = ' . ($model->dimensionAnswers[$key] - $compare->dimensionAnswers[$key]);
            }
            echo '<br />';
        }
        ?>
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
