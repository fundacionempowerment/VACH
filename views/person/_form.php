<?php

use app\models\Person;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$genders = Person::getGenders();
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user', 'Please fill out the following fields with person data:') ?></p>

    <?php
    $form = ActiveForm::begin([
        'id' => 'newperson-form',
    ]);
    ?>

    <div class="col-md-6">
        <?= $form->field($person, 'name') ?>
        <?= $form->field($person, 'surname') ?>
        <?= $form->field($person, 'shortname') ?>
        <?= $form->field($person, 'email') ?>
        <?= $form->field($person, 'phone') ?>
        <?= $form->field($person, 'gender')->widget(Select2::classname(), ['data' => $genders]) ?>
        <?= $form->field($person, 'notes')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($person, 'photo')->widget(FileInput::classname(), [
            'pluginOptions' => [
                'showPreview' => true,
                'showCaption' => false,
                'showRemove' => true,
                'showUpload' => false,
                'overwriteInitial' => true,
                'initialPreview' => ($person->photo ? [Html::img($person->photoUrl, ['class' => 'file-preview-image img-responsive', 'alt' => '', 'title' => ''])] : false),
            ]]);
        ?>
    </div>
    <div class="clearfix"></div>

    <div class="form-group">
        <?= \app\components\SpinnerSubmitButton::widget([
            'caption' => \Yii::t('app', 'Save'),
            'options' => ['class' => 'btn ' . ($person->isNewRecord ? 'btn-success' : 'btn-primary')]
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
