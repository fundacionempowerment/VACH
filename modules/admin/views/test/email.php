<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Test email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-email-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-12">

            <?php $form = ActiveForm::begin(['id' => 'test-email-form']); ?>

            <label>To:</label>
            <?= Html::textInput('email', $email, ['class' => 'form-control']) ?>

            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>