<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$gaugeRadio = 200;
$token = rand(100000, 999999);

$dimensions = WheelQuestion::getDimensionNames($type);
?>
<h3><?= $title ?></h3>
<?php for ($i = 0; $i < 8; $i++) { ?>
    <div class="col-md-3" >
        <label><?= $dimensions[$i] ?></label>
        <?php
        if ($wheel[$i] > 3.5)
            $class = 'progress-bar-info';
        else if ($wheel[$i] < 1)
            $class = 'progress-bar-danger';
        else if ($wheel[$i] < 2)
            $class = 'progress-bar-warning';
        else
            $class = 'progress-bar-info';

        echo Progress::widget([
            'percent' => $wheel[$i] / 4 * 100,
            'label' => floor($wheel[$i] / 4 * 100) . ' %',
            'barOptions' => ['class' => $class],
        ]);
        ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
