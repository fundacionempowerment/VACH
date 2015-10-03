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
    <div class="col-md-3" >
        <label><?= $dimensions[$i] ?></label>
        <b>
            <?php
            if ($gauges[$i] > Yii::$app->params['good_consciousness'])
                $class = 'progress-bar-success';
            else if ($gauges[$i] < Yii::$app->params['minimal_consciousness'])
                $class = 'progress-bar-danger';
            else
                $class = 'progress-bar-warning';

            echo Progress::widget([
                'percent' => $gauges[$i] / 4 * 100,
                'label' => floor($gauges[$i] / 4 * 100) . ' %',
                'barOptions' => ['class' => $class],
            ]);
            ?>
        </b>
    </div>
<?php } ?>
<div class="clearfix"></div>
