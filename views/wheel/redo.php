<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('wheel', 'Redo wheel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Wheels'), 'url' => ['/wheel']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $form = ActiveForm::begin([
        'id' => 'redo-wheel-form',
    ]);
    ?>
    <div class='text-center'>
        <h3 class='alert alert-danger'><?= Yii::t('app', 'This action cannot be reverted.') ?></h3>
        <h3><?= Yii::t('team', 'Are you sure you want to discard and redo this wheel?') ?></h3>
        <div class="form-group">
            <?= Html::hiddenInput('delete', 1) ?>
            <?= Html::submitButton(\Yii::t('wheel', 'Discard and redo'), ['class' => 'btn btn-danger', 'name' => 'delete-button']) ?>
            <?= Html::a(\Yii::t('app', 'Cancel'), ['/wheel',], ['class' => 'btn btn-default', 'name' => 'cancel-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
