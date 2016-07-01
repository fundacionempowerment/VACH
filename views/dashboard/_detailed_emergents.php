<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use app\controllers\Utils;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if ($type == Wheel::TYPE_GROUP)
    $title = Yii::t('dashboard', 'Group Emergents Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organiz. Emergents Matrix');
else
    $title = Yii::t('dashboard', 'Individual Emergents Matrix');

if (!empty($member)) {
    $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
} else {
    $title .= ' ' . Yii::t('app', 'of the team');
}

$dimensions = WheelQuestion::getDimensionNames($type);
$questionCount = WheelQuestion::getQuestionCount($type) / 8;

$index = $type == Wheel::TYPE_INDIVIDUAL ? 'mine_value' : 'value';

$token = rand(100000, 999999);

function compare_emergents($a, $b) {
    $va = $a['value'];
    $vb = $b['value'];
    if ($va == $vb) {
        return 0;
    } else if ($va > $vb) {
        return -1;
    } else {
        return 1;
    }
}

uasort($emergents, 'compare_emergents');
?>
<div class="row col-md-12">
    <h4>
        <?= Yii::t('dashboard', 'All emergents') ?>
        <a class="collapsed btn btn-default" aria-controls="collapsedDiv" aria-expanded="false" href="#collapsedDiv" data-toggle="collapse" role="button">
            <?= Yii::t('app', 'Show') ?>
        </a>
    </h4>
    <div id="collapsedDiv" class="panel-collapse collapse row col-md-12" aria-expanded="false">
        <?php
        foreach ($emergents as $emergent) {
            if ($emergent['answer_order'] % $questionCount != $questionCount - 1) {
                ?>
                <label><?= $dimensions[$emergent['dimension']] ?> - <?= $emergent['question'] ?></label>
                <?php
                if ($emergent[$index] > Yii::$app->params['good_consciousness'])
                    $color = '5cb85c';
                else if ($emergent[$index] < Yii::$app->params['minimal_consciousness'])
                    $color = 'd9534f';
                else
                    $color = 'f0ad4e';

                $percentage = $emergent[$index] / 4 * 100;
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
        }
        ?>
    </div>
</div>
