<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$dimensions = WheelQuestion::getDimensionNames($type);

$current_dimension = -1;
?>
<h3><?= $title ?></h3>
<?php foreach ($emergents as $emergent) { ?>
    <?php
    if ($emergent['dimension'] != $current_dimension) {
        $current_dimension = $emergent['dimension'];
        ?>
        <h4><?= $dimensions[$current_dimension] ?></h4>
    <?php } ?>
    <div class="col-md-12" >
        <label><?= $emergent['question'] ?></label>
        <?php
        if ($emergent['value'] > 3.5)
            $class = 'progress-bar-info';
        else if ($emergent['value'] < 1)
            $class = 'progress-bar-danger';
        else if ($emergent['value'] < 2)
            $class = 'progress-bar-warning';
        else
            $class = 'progress-bar-active';

        echo Progress::widget([
            'percent' => $emergent['value'] / 4 * 100,
            'label' => floor($emergent['value'] / 4 * 100) . ' %',
            'barOptions' => ['class' => $class],
        ]);
        ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
