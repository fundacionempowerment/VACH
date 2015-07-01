<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('goal', 'New goal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/person']];
$this->params['breadcrumbs'][] = ['label' => $goal->person->fullname, 'url' => ['/person/view', 'id' => $goal->person->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="new-goal">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'goal-form']); ?>
    <?= $form->field($goal, 'name') ?>
    <?= Html::submitButton(Yii::t('goal', 'Save and continue...'), ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>
</div>