<?php

use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $team->id == 0 ? Yii::t('team', 'New team') : $team->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
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
    $form->field($team, 'team_type_id')->widget(Select2::classname(), [
        'data' => app\models\TeamType::getList(),
    ]);
    ?>
    <?=
    $form->field($team, 'company_id')->widget(Select2::classname(), [
        'data' => $companies,
    ]);
    ?>
    <?=
    $form->field($team, 'sponsor_id')->widget(Select2::classname(), [
        'data' => $persons,
    ]);
    ?>
    <?= $form->field($team, 'notes')->textarea() ?>
    <div class="form-group">
        <?= \app\components\SpinnerSubmitButton::widget([
            'caption' => \Yii::t('app', 'Save'),
            'options' => ['class' => 'btn ' . ($team->isNewRecord ? 'btn-success' : 'btn-primary')]
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
