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
            <div class="col-sm-push-4 col-sm-4">
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['login']]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="row col-xs-2">
                    <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <div class="text-right">
                    <?= Html::a(Yii::t('app', 'Sign up'), ['site/register'], ['class' => 'btn btn-default']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="clearfix" >
            </div>
            <div class="col-sm-push-5 col-sm-2 text-center" style="margin-top: 30px;">    
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
