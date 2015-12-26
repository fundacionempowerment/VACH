<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user', 'Please fill out the following fields with person data:') ?></p>

    <?php
    $form = ActiveForm::begin([
                'id' => 'newperson-form',
    ]);
    ?>

    <?= $form->field($person, 'name') ?>
    <?= $form->field($person, 'surname') ?>
    <?= $form->field($person, 'email') ?>
    <?= $form->field($person, 'phone') ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn ' . ($person->isNewRecord ? 'btn-success' : 'btn-primary'), 'name' => 'save-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>