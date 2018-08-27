<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'Set password to {name}', ['name' => $user->fullname]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="password-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php
            $form = ActiveForm::begin(['id' => 'password-form',]);
            ?>
            <?= $form->field($user, 'password')->passwordInput() ?>
            <?= $form->field($user, 'password_confirm')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>