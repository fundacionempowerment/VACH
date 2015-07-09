<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'VACH';
?>
<div class="site-index">
    <div class="jumbotron">
        <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-md-push-4 col-md-4">
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['login']]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <?php $wheelForm = ActiveForm::begin(['id' => 'token-form', 'action' => ['token'],]); ?>
                <?= $wheelForm->field($wheel, 'token') ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Run'), ['class' => 'btn btn-primary', 'name' => 'run-button']) ?>                    
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
