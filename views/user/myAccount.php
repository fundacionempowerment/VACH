<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'My Data');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?=
        Yii::t('user', 'Please fill out the following fields with your personal data:')
        ?>
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php
            $form = ActiveForm::begin(['id' => 'account-form',]);
            ?>

            <?= $form->field($user, 'name') ?>
            <?= $form->field($user, 'surname') ?>
            <?= $form->field($user, 'email') ?>
            <?= $form->field($user, 'phone') ?>
            <?= $form->field($user, 'username') ?>
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>