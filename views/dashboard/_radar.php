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

$individual_dimensions = WheelQuestion::getDimensionNames(Wheel::TYPE_INDIVIDUAL);

$dimensions = WheelQuestion::getDimensionNames($type);
for ($i = 0; $i < count($dimensions); $i++) {
    if ($type > Wheel::TYPE_INDIVIDUAL) {
        $dimensions[$i] = $individual_dimensions[$i] . '/' . $dimensions[$i];
    }

    $dimensions[$i] = str_replace('Orientación', 'O.', $dimensions[$i]);
    $dimensions[$i] = str_replace('Orientation', 'O.', $dimensions[$i]);
    $dimensions[$i] = str_replace('Dimensión', 'D.', $dimensions[$i]);
    $dimensions[$i] = str_replace('Dimension', 'D.', $dimensions[$i]);
}
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="col-xs-push-1 col-xs-10 col-md-push-2 col-md-8 text-center" >
    <canvas id="canvas<?= $token ?>" height="<?= $radarDiameter * 0.6 ?>" width="<?= $radarDiameter ?>" class="img-responsive"></canvas>
    <p>
        <span style="color: red;"><?= $wheelName ?></span>
        <?php if (isset($comparedWheel)) { ?>
            <span style="color: blue;"><?= $comparedWheelName ?></span>
        <?php } ?>
    </p>
</div>
<div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app','Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
</div>
<div class="clearfix"></div>
<script>
    var data<?= $token ?> = {
    labels: [<?= '"' . implode('", "', $dimensions) . '"' ?>],
            datasets: [
<?php if (isset($comparedWheel)) { ?>
        {
        label: "<?= $comparedWheelName ?> ",
                fillColor: "rgba(0,0,255,0.2)",
                strokeColor: "rgba(0,0,255,1)",
                pointColor: "rgba(0,0,255,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?= '"' . implode('", "', $comparedWheel) . '"' ?>]
        },
<?php } ?>
    {
    label: "<?= $wheelName ?>",
            fillColor: "rgba(255,0,0,0.2)",
            strokeColor: "rgba(255,0,0,1)",
            pointColor: "rgba(255,0,0,1)",
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