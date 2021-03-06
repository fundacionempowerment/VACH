<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = \Yii::t('app', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= \Yii::t('app', 'Please choose your new password:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <h4><?= Yii::t('user', 'User') ?>: <strong><?= $model->username ?></strong></h4>

            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password_confirm')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
