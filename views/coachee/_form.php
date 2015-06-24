<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user', 'Please fill out the following fields with coachee data:') ?></p>

    <?php
    $form = ActiveForm::begin([
                'id' => 'newcoachee-form',
    ]);
    ?>

    <?= $form->field($coachee, 'name') ?>
    <?= $form->field($coachee, 'surname') ?>
    <?= $form->field($coachee, 'email') ?>
    <?= $form->field($coachee, 'phone') ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>