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
    $title = Yii::t('dashboard', 'Group Emergents Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Emergents Matrix');
else
    $title = Yii::t('dashboard', 'Individual Emergents Matrix');

$dimensions = WheelQuestion::getDimensionNames($type);
$questionCount = WheelQuestion::getQuestionCount($type) / 8;

$selected_emergents = [];

for ($current_dimension = 0; $current_dimension < 8; $current_dimension++) {
    $maxValue = -100;
    $minValue = 100;

    foreach ($emergents as $emergent)
        if ($emergent['dimension'] == $current_dimension)
            if ($emergent['answer_order'] % $questionCount != $questionCount - 1) {
                if ($emergent['value'] > Yii::$app->params['good_consciousness'] && $emergent['value'] > $maxValue)
                    $maxValue = $emergent['value'];
                if ($emergent['value'] < Yii::$app->params['minimal_consciousness'] && $emergent['value'] < $minValue)
                    $minValue = $emergent['value'];
            }

    foreach ($emergents as $emergent)
        if ($emergent['dimension'] == $current_dimension)
            if ($emergent['answer_order'] % $questionCount != $questionCount - 1) {
                if ($emergent['value'] == $maxValue || $emergent['value'] == $minValue)
                    $selected_emergents[] = $emergent;
            }
}

$current_dimension = -1;
$token = rand(100000, 999999);
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="row col-md-12">
    <?php
    for ($current_dimension = 0; $current_dimension < 8; $current_dimension++) {
        if ($current_dimension % 2 == 0)
            echo '<div class="clearfix"></div>';
        ?>
        <div class="col-sm-6">
            <h4><?= $dimensions[$current_dimension] ?></h4>
            <?php
            foreach ($selected_emergents as $emergent)
                if ($emergent['dimension'] == $current_dimension && ($emergent['value'] > Yii::$app->params['good_consciousness'] || $emergent['value'] < Yii::$app->params['minimal_consciousness'])) {
                    ?>
                    <label><?= $emergent['question'] ?></label>
                    <?php
                    if ($emergent['value'] > Yii::$app->params['good_consciousness'])
                        $color = '5cb85c';
                    else if ($emergent['value'] < Yii::$app->params['minimal_consciousness'])
                        $color = 'd9534f';
                    else
                        $color = 'f0ad4e';

                    $percentage = $emergent['value'] / 4 * 100;
                    if ($percentage < 6)
                        $width = 6;
                    else
                        $width = $percentage;
                    ?>
                    <div style='position:relative; color: white; font-size: 20px;' class="table table-bordered">
                        <div style='font-size:0px; border-top: 28px solid #<?= $color ?>; width: <?= $width ?>%;'>&nbsp;</div>
                        <div style='position:absolute; top:0px; left: 5px;'><?= floor($percentage) ?>%</div>
                    </div>
                    <?php
                }
            ?>
        </div>
    <?php } ?>
</div>
<div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app','Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
</div>
<div class="clearfix"></div>
