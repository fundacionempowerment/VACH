<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if ($type == Wheel::TYPE_GROUP)
    $title = Yii::t('dashboard', 'Group Perception Adjustment Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Perception Adjustment Matrix');
else
    $title = Yii::t('dashboard', 'Individual Perception Adjustment Matrix');

if (!empty($member)) {
    $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
} else {
    $title .= ' ' . Yii::t('app', 'of the team');
}

$linealWidth = 350 * 1.5;
$linealHeight = 200;
$token = rand(100000, 999999);

$dimensions = WheelQuestion::getDimensionNames($type, true);
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="col-sm-12 col-md-push-2 col-md-8 text-center" >
    <canvas id="canvas<?= $token ?>" height="<?= $linealHeight ?>" width="<?= $linealWidth ?>" class="img-responsive"></canvas><br />
    <p>
        <span style="color: red;"><?= $comparedWheelName ?></span>
        <?php if (isset($comparedWheel)) { ?>
            <span style="color: blue;"><?= $wheelName ?></span>
        <?php } ?>
    </p>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'dashboard') === true) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
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
            lineals.push("<?= $token ?>");
            linealsData.push(data<?= $token ?>);
</script>