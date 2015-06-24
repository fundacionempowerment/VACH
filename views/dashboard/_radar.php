<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$radarDiameter = 350;
$token = rand(100000, 999999);

$dimensions = WheelQuestion::getDimensionNames($type);
?>
<h3><?= $title ?></h3>
<div class="col-xs-push-2 col-xs-8 col-md-push-4 col-md-4" >
    <canvas id="canvas<?= $token ?>" height="<?= $radarDiameter ?>" width="<?= $radarDiameter ?>" class="img-responsive"></canvas>
    <p>
        <span style="color: blue;"><?= $wheelName ?></span>
        <?php if (isset($comparedWheel)) { ?>
            <span style="color: red;"><?= $comparedWheelName ?></span>
        <?php } ?>
    </p>
</div>
<div class="clearfix"></div>
<script>
    var data<?= $token ?> = {
    labels: [<?= '"' . implode('", "', $dimensions) . '"' ?>],
            datasets: [
<?php if (isset($comparedWheel)) { ?>
        {
        label: "<?= $comparedWheelName ?> ",
                fillColor: "rgba(255,0,0,0.2)",
                strokeColor: "rgba(255,0,0,1)",
                pointColor: "rgba(255,0,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?= '"' . implode('", "', $comparedWheel) . '"' ?>]
        },
<?php } ?>
    {
    label: "<?= $wheelName ?>",
            fillColor: "rgba(0,0,255,0.2)",
            strokeColor: "rgba(0,0,255,1)",
            pointColor: "rgba(0,0,255,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?= '"' . implode('", "', $wheel) . '"' ?>]
    },
    ]
    };
            radars.push("<?= $token ?>");
            radarsData.push(data<?= $token ?>);
</script>