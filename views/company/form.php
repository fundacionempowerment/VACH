<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $company->id == 0 ? Yii::t('company', 'New company') : $company->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'My Companies'), 'url' => ['/company']];
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
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
