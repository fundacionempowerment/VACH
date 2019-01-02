<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $company->id == 0 ? Yii::t('company', 'New company') : $company->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Companies'), 'url' => ['/company']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('company', 'Please fill out the following fields with company data:') ?></p>

    <?php
    $form = ActiveForm::begin([
        'id' => 'newcompany-form',
    ]);
    ?>

    <?= $form->field($company, 'name') ?>
    <?= $form->field($company, 'email') ?>
    <?= $form->field($company, 'phone') ?>
    <?= $form->field($company, 'notes')->textarea() ?>
    <div class="form-group">
        <?= \app\components\SpinnerSubmitButton::widget([
            'caption' => \Yii::t('app', 'Save'),
            'options' => ['class' => 'btn ' . ($company->isNewRecord ? 'btn-success' : 'btn-primary')]
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
