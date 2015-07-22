<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('user', 'My account');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="body-content">
        <div class="row">
            <div class="col-md-4">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'account-form',
                ]);
                ?>

                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'surname') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'oldPassword')->passwordInput() ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'confirm')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                    <?= Html::a(\Yii::t('app', 'Cancel'), ['/site'], ['class' => 'btn', 'name' => 'cancel-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
