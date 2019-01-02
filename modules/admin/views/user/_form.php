<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$isAdministrator = Yii::$app->user->identity->is_administrator;
?>
<div class="user-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?=
        Yii::$app->user->id == $user->id ?
            Yii::t('user', 'Please fill out the following fields with your personal data:') :
            Yii::t('user', 'Please fill out the following fields with user personal data:')
        ?>
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php
            $form = ActiveForm::begin([
                'id' => 'account-form',
            ]);
            ?>

            <?= $form->field($user, 'name') ?>
            <?= $form->field($user, 'surname') ?>
            <?= $form->field($user, 'email') ?>
            <?= $form->field($user, 'phone') ?>
            <?= $form->field($user, 'username') ?>
            <?= $user->password_hash ? '' : $form->field($user, 'resetPassword')->checkbox() ?>
            <?= $isAdministrator ? $form->field($user, 'is_administrator')->checkbox() : '' ?>
            <?= $form->field($user, 'notes')->textarea() ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>