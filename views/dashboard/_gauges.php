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

$dimensions = WheelQuestion::getDimensionNames($type);
$token = rand(100000, 999999);
?>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="row col-md-push-1 col-md-10">
    <?php for ($i = 0; $i < 8; $i++) { ?>
        <div class="col-xs-4" >
            <label><?= $dimensions[$i] ?></label>
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
            <div style='position:relative; color: white; font-size: 20px;' class="table table-bordered">
                <div style='font-size:0px; border-top: 28px solid #<?= $color ?>; width: <?= $width ?>%;'>&nbsp;</div>
                <div style='position:absolute; top:0px; left: 5px;'><?= floor($percentage) ?>%</div>
            </div>
        </div>
    <?php } ?>
</div>
<div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app','Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
</div>
<div class="clearfix"></div>
