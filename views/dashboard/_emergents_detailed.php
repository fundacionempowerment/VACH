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
    $title = Yii::t('dashboard', 'Detailed Group Emergents Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Detailed Organizational Emergents Matrix');
else
    $title = Yii::t('dashboard', 'Detailed Individual Emergents Matrix');

$dimensions = WheelQuestion::getDimensionNames($type);

$current_dimension = -1;
?>
<div class="clearfix"></div>
<h3>
    <?= $title ?>
    <a class="collapsed btn btn-default" aria-controls="collapsedDiv" aria-expanded="false" href="#collapsedDiv" data-toggle="collapse" role="button">
        <?= Yii::t('dashboard', 'Show') ?>
    </a>
</h3>
<div id="collapsedDiv" class="panel-collapse collapse" aria-labelledby="collapseListGroupHeading1" role="tabpanel" aria-expanded="false" style="height: 0px;">
    <?php
    for ($current_dimension = 0; $current_dimension < 8; $current_dimension++) {
        if ($current_dimension % 2 == 0)
            echo '<div class="clearfix"></div>';
        ?>
        <div class="col-sm-6">
            <h4><?= $dimensions[$current_dimension] ?></h4>
            <?php
            foreach ($emergents as $emergent)
                if ($emergent['dimension'] == $current_dimension && ($emergent['value'] > Yii::$app->params['good_consciousness'] || $emergent['value'] < Yii::$app->params['minimal_consciousness'])) {
                    ?>
                    <label><?= $emergent['question'] ?></label>
                    <?php
                    if ($emergent['value'] > Yii::$app->params['good_consciousness'])
                        $color = 'dff0d8';
                    else if ($emergent['value'] < Yii::$app->params['minimal_consciousness'])
                        $color = 'f2dede';
                    else
                        $color = 'faf2cc';

                    $percentage = $emergent['value'] / 4 * 100;
                    if ($percentage < 5)
                        $width = 5;
                    else
                        $width = $percentage;
                    ?>
                    <div style='position:relative;' class="table table-bordered">
                        <div style='font-size:0px;  border-top: 20px solid #<?= $color ?>; width: <?= $width ?>%;'>&nbsp;</DIV>
                        <div style='position:absolute; top:0px; left: 5px;'><?= floor($percentage) ?>%</DIV>
                    </div>
                    <?php
                }
            ?>
        </div>
    <?php } ?>
</div>
<div class="clearfix"></div>
