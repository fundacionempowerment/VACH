<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use \yii\widgets\MaskedInput;

/* @var $this yii\web\View */
$this->title = 'VACH';
?>
<div class="site-index">
    <div class="jumbotron">
        <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
        <?php if (Yii::$app->params['server_status'] != 'online') { ?>
            <h5 class="text-info">
                <?= Yii::$app->params['server_status'] ?>
            </h5>
        <?php } ?>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-sm-push-4 col-sm-4 text-center">
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['login']]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?> 
                <?= Html::a(Yii::t('app', 'Sign up'), ['site/register'], ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end(); ?>
                <br />
                <br />
                <br />
                <?php $wheelForm = ActiveForm::begin(['id' => 'token-form', 'action' => ['token'],]); ?>
                <label class="control-label"><?= Yii::t('wheel', 'Wheel token') ?></label><br>
                <?= Html::textInput('token1', '', ['size' => '4', 'maxlength' => '3']) ?>
                -
                <?= Html::textInput('token2', '', ['size' => '4', 'maxlength' => '3']) ?>
                -
                <?= Html::textInput('token3', '', ['size' => '4', 'maxlength' => '3']) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Run'), ['class' => 'btn btn-primary', 'name' => 'run-button']) ?>                    
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
