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
    $title = Yii::t('dashboard', 'Group Potential Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Potential Matrix');
else
    $title = Yii::t('dashboard', 'Individual Potential Matrix');

$dimensions = WheelQuestion::getDimensionNames($type);

$current_dimension = -1;
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<?php
foreach ($emergents as $emergent)
    if ($emergent['value'] > Yii::$app->params['good_consciousness'] || $emergent['value'] < Yii::$app->params['minimal_consciousness']) {
        ?>
        <?php
        if ($emergent['dimension'] != $current_dimension) {
            $current_dimension = $emergent['dimension'];
            ?>
            <h4><?= $dimensions[$current_dimension] ?></h4>
        <?php } ?>
        <div class="col-md-12" >
            <label><?= $emergent['question'] ?></label>
            <?php
            if ($emergent['value'] > Yii::$app->params['good_consciousness'])
                $class = 'progress-bar-success';
            else if ($emergent['value'] < Yii::$app->params['minimal_consciousness'])
                $class = 'progress-bar-danger';
            else
                $class = 'progress-bar-warning';


            if ($emergent['value'] == 0) {
                echo Progress::widget([
                    'percent' => $emergent['value'] / 4 * 100,
                    'label' => floor($emergent['value'] / 4 * 100) . ' %',
                    'barOptions' => ['class' => $class, 'style' => 'width: 3%;'
                    ],
                ]);
            } else {

                echo Progress::widget([
                    'percent' => $emergent['value'] / 4 * 100,
                    'label' => floor($emergent['value'] / 4 * 100) . ' %',
                    'barOptions' => ['class' => $class],
                ]);
            }
            ?>
        </div>
    <?php } ?>
<div class="clearfix"></div>
