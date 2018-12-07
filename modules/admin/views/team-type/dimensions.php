<?php

use app\models\WheelQuestion;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $teamType app\models\TeamType */

$this->title = ($teamType->id == 0 ? Yii::t('team', 'New team type') : Yii::t('app', 'Edit') . ' ' . $teamType->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Team Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $teamType->name, 'url' => ['view', 'id' => $teamType->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'questions-form']); ?>
    <h3><?= $teamType->getAttributeLabel('wheelDimensions') ?></h3>
    <div class="row col-xs-12">
        <?php
        $current_level = -1;
        $div_open = false;

        foreach ($teamType->dimensions as $dimension) {
            if (!$teamType->isLevelEnabled($dimension->level)) {
                continue;
            }

            if ($current_level != $dimension->level) {
                $current_level = $dimension->level;

                if ($div_open == true) {
                    echo '</div>';
                    $div_open = false;
                }

                echo '<div class="box"><h3>' . $teamType->levelName($current_level) . '</h3>';
                $div_open = true;
            }
            ?>
            <p>
                <?= Html::input('text', "d-$dimension->level-$dimension->order", $dimension->name, ['class' => 'col-xs-12']) ?>
            </p>
        <?php } ?>
        <?= '</div>' ?>
    </div>
    <p>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
    </p>
    <?php ActiveForm::end(); ?>
</div>
