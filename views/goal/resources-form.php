<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;

$required = "";
$preventing = "";
foreach ($goal->resources as $resource) {
    if ($resource->is_desired)
        $required .= $resource->description . PHP_EOL;
    else
        $preventing .= $resource->description . PHP_EOL;
}

$this->title = Yii::t('goal', 'Goal resources');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = ['label' => $goal->coachee->fullname, 'url' => ['/coachee/view', 'id' => $goal->coachee->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('goal', 'Goal - ') . $goal->name, 'url' => ['/goal/view', 'id' => $goal->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="new-goal">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'resources-form']); ?>
    <div class="row">
        <div class="col-md-12">
            <?= Yii::t('goal', 'Please, describe all the things <b>required</b> to achieve your goal, one line each:') ?><br />
            <?= Html::textarea('required', $required, ['class' => 'col-xs-12', 'rows' => '10']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-12">
            <?= Yii::t('goal', 'Please, describe all the things that <b>prevent</b> your goal to be achieved, one line each:') ?><br />
            <?= Html::textarea('prevent', $preventing, ['class' => 'col-xs-12', 'rows' => '10']) ?>
        </div>
    </div>
    <br />
    <?= Html::submitButton(Yii::t('goal', 'Save and continue...'), ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>
</div>