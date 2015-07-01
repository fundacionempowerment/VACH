<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;

$this->title = Yii::t('goal', 'Goal resources assigment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/person']];
$this->params['breadcrumbs'][] = ['label' => $goal->person->fullname, 'url' => ['/person/view', 'id' => $goal->person->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('goal', 'Goal - ') . $goal->name, 'url' => ['/goal/view', 'id' => $goal->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="new-goal">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'resources-form']); ?>
    <div class="row">
        <div class="col-md-12">
            <?= Yii::t('goal', 'Please, select those resources you currently have:') ?>
        </div>
        <?php foreach ($goal->resources as $resource): ?>
            <div class="col-md-12">
                <?= Html::checkbox('have' . $resource->id, $resource->is_had) ?> <?= $resource->description ?>
            </div>
        <?php endforeach; ?>
    </div>

    <br />
    <?= Html::submitButton(Yii::t('goal', 'Save and continue...'), ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>
</div>