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
                    <?= Html::a(Yii::t('app', 'Sign up'), Url::to(['register']), ['class' => 'btn btn-success']) ?>
                </div>
                <div style="color:#999;margin:1em 0">
                    <?= \Yii::t('user', 'If you forgot your password you can ') ?>
                    <?= Html::a(\Yii::t('user', 'reset it'), ['site/request-password-reset']) ?>.
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
