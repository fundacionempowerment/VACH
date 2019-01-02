<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\TeamType */

$this->title = ($teamType->id == 0 ? Yii::t('team', 'New team type') : Yii::t('app', 'Edit') . ' ' . $teamType->name );
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Team Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $teamType->name, 'url' => ['view', 'id' => $teamType->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'questions-form']); ?>
    <?= $form->field($teamType, 'name') ?>
    <?= $form->field($teamType, 'product_id')->dropDownList(\app\models\Product::getList()) ?>
    <?= $form->field($teamType, 'level_0_enabled')->checkbox() ?>
    <?= $form->field($teamType, 'level_0_name') ?>
    <?= $form->field($teamType, 'level_1_enabled')->checkbox() ?>
    <?= $form->field($teamType, 'level_1_name') ?>
    <?= $form->field($teamType, 'level_2_enabled')->checkbox() ?>
    <?= $form->field($teamType, 'level_2_name') ?>
    <p>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['name' => 'action', 'value' => 'save', 'class' => 'btn btn-primary']); ?>
        <?= Html::submitButton(Yii::t('app', 'Dimensions'), ['name' => 'action', 'value' => 'dimensions', 'class' => 'btn btn-info']); ?>
        <?= Html::submitButton(Yii::t('app', 'Questions'), ['name' => 'action', 'value' => 'questions', 'class' => 'btn btn-success']); ?>
    </p>
    <?php ActiveForm::end(); ?>
</div>
