<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $client->id == 0 ? Yii::t('user', 'New client') : Yii::t('user', 'Editing client');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Clients'), 'url' => ['/coach/clients']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user', 'Please fill out the following fields with client data:') ?></p>

    <?php
    $form = ActiveForm::begin([
                'id' => 'newclient-form',
    ]);
    ?>

    <?= $form->field($client, 'name') ?>
    <?= $form->field($client, 'surname') ?>
    <?= $form->field($client, 'email') ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
        <?= Html::a(\Yii::t('app', 'Cancel'), ['/site'], ['class' => 'btn', 'name' => 'cancel-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
