<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $team->id == 0 ? Yii::t('team', 'New team') : $team->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'My Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('team', 'Please fill out the following fields with team data:') ?></p>
    <?php
    $form = ActiveForm::begin([
                'id' => 'newteam-form',
    ]);
    ?>
    <?= $form->field($team, 'name') ?>
    <?=
    $form->field($team, 'company_id')->widget(Select2::classname(), [
        'data' => $companies,
    ]);
    ?>
    <?=
    $form->field($team, 'sponsor_id')->widget(Select2::classname(), [
        'data' => $coachees,
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
