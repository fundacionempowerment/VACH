<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if ($type == Wheel::TYPE_GROUP)
    $title = Yii::t('dashboard', 'Group Competence Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Competence Matrix');
else
    $title = Yii::t('dashboard', 'Individual Competence Matrix');

if (!empty($member)) {
    $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
} else {
    $title .= ' ' . Yii::t('app', 'of the team');
}

$minValue = 1000;
$maxValue = -1000;

foreach ($gauges as $gauge) {
    if ($gauge < $minValue) {
        $minValue = $gauge;
    }
    if ($gauge > $maxValue) {
        $maxValue = $gauge;
    }
}

$maxShadow = "box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 6px #67b168";
$minShadow = "box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 6px #ce8483";

$token = rand(100000, 999999);
?>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="row col-md-12">
    <?php for ($i = 0; $i < 8; $i++) { ?>
        <div class="col-xs-4 active"">
            <?= $type == Wheel::TYPE_INDIVIDUAL ? '<b>' : '' ?>
            <?= WheelQuestion::getDimentionName($i, Wheel::TYPE_INDIVIDUAL, true) ?>
            <?= $type == Wheel::TYPE_INDIVIDUAL ? '</b>' : '' ?>
            -
            <?= $type == Wheel::TYPE_GROUP ? '<b>' : '' ?>
            <?= WheelQuestion::getDimentionName($i, Wheel::TYPE_GROUP, true) ?>
            <?= $type == Wheel::TYPE_GROUP ? '</b>' : '' ?>
            -
            <?= $type == Wheel::TYPE_ORGANIZATIONAL ? '<b>' : '' ?>
            <?= WheelQuestion::getDimentionName($i, Wheel::TYPE_ORGANIZATIONAL, true) ?>
            <?= $type == Wheel::TYPE_ORGANIZATIONAL ? '</b>' : '' ?>
            <?php
            if ($gauges[$i] > Yii::$app->params['good_consciousness'])
                $color = '5cb85c';
            else if ($gauges[$i] < Yii::$app->params['minimal_consciousness'])
                $color = 'd9534f';
            else
                $color = 'f0ad4e';
            $percentage = $gauges[$i] / 4 * 100;
            if ($percentage < 5)
                $width = 5;
            else
                $width = $percentage;
            ?>
            <div style='position:relative; color: white; font-size: 20px; <?php
            if ($gauges[$i] == $minValue) {
                echo $minShadow;
            } else if ($gauges[$i] == $maxValue) {
                echo $maxShadow;
            }
            ?>' class="table table-bordered">
                <div style='font-size:0px; border-top: 28px solid #<?= $color ?>; width: <?= $width ?>%;'>&nbsp;</div>
                <div style='position:absolute; top:0px; left: 5px;'><?= round($percentage * 10) / 10 ?>%</div>
            </div>
        </div>
    <?php } ?>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'dashboard') === true) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
