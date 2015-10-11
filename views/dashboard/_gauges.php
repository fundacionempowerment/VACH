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
    $title = Yii::t('dashboard', 'Group Competence Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Competence Matrix');
else
    $title = Yii::t('dashboard', 'Individual Competence Matrix');

$dimensions = WheelQuestion::getDimensionNames($type);
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<?php for ($i = 0; $i < 8; $i++) { ?>
    <div class="col-xs-3" >
        <label><?= $dimensions[$i] ?></label>
        <?php
        if ($gauges[$i] > Yii::$app->params['good_consciousness'])
            $color = 'dff0d8';
        else if ($gauges[$i] < Yii::$app->params['minimal_consciousness'])
            $color = 'f2dede';
        else
            $color = 'faf2cc';
        $percentage = $gauges[$i] / 4 * 100;
        if ($percentage < 5)
            $width = 5;
        else
            $width = $percentage;
        ?>
        <div style='position:relative;' class="table table-bordered">
            <div style='font-size:0px;  border-top: 20px solid #<?= $color ?>; width: <?= $width ?>%;'>&nbsp;</DIV>
            <div style='position:absolute; top:0px; left: 5px;'><?= floor($percentage) ?>%</DIV>
        </div>
    </div>
<?php } ?>
<div class="clearfix"></div>
