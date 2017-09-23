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
        <?= Html::img('@web/images/logo.png') ?>
        <?php if (Yii::$app->params['server_status'] != 'online') { ?>
            <h5 class="text-info">
                <?= Yii::$app->params['server_status'] ?>
            </h5>
        <?php } ?>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-xs-push-2 col-xs-8 col-sm-push-2 col-sm-8 col-md-push-4 col-md-4 ">
                <?= app\widgets\Alert::widget() ?>
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['login']]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-vach col-md-12', 'name' => 'login-button']) ?><br />
                </div>
                <div class="text-right form-group">
                    <?= Html::a(Yii::t('app', 'Forgor your password?'), ['site/request-password-reset'], ['class' => 'text-danger']) ?>
                </div>
                <div class="text-center">
                    <?= Yii::$app->params['allow_register'] ? Html::a(Yii::t('app', 'Sign up'), ['site/register'], ['class' => 'btn btn-default']) : '' ?> 
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="clearfix" >
            </div>
            <div class="col-xs-push-3 col-xs-6 col-sm-push-4 col-sm-3 col-md-push-4 col-md-4 text-center" style="margin-top: 30px;">    
                <?php $wheelForm = ActiveForm::begin(['id' => 'token-form', 'action' => ['token'],]); ?>
                <?= $wheelForm->field($wheel, 'token') ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Run'), ['class' => 'btn btn-vach-border col-md-12', 'name' => 'run-button']) ?>                    
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
