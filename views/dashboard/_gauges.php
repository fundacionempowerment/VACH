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
    $title = Yii::t('dashboard', 'Group Indicators');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Indicators');
else
    $title = Yii::t('dashboard', 'Individual Indicators');

$gaugeRadio = 200;
$token = rand(100000, 999999);

$dimensions = WheelQuestion::getDimensionNames($type);
?>
<h3><?= $title ?></h3>
<?php for ($i = 0; $i < 8; $i++) { ?>
    <div class="col-md-3" >
        <label><?= $dimensions[$i] ?></label>
        <?php
        if ($wheel[$i] > Yii::$app->params['good_consciousness'])
            $class = 'progress-bar-success';
        else if ($wheel[$i] < Yii::$app->params['minimal_consciousness'])
            $class = 'progress-bar-danger';
        else
            $class = 'progress-bar-warning';

        echo Progress::widget([
            'percent' => $wheel[$i] / 4 * 100,
            'label' => floor($wheel[$i] / 4 * 100) . ' %',
            'barOptions' => ['class' => $class],
        ]);
        ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
