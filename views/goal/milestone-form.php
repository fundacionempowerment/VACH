<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use app\models\GoalMilestone;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $milestone->id == 0 ?  Yii::t('goal', 'New goal milestone') : Yii::t('goal', 'Editing goal milestone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/person']];
$this->params['breadcrumbs'][] = ['label' => $goal->person->fullname, 'url' => ['/person/view', 'id' => $goal->person->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('goal', 'Goal - ') . $goal->name, 'url' => ['/goal/view', 'id' => $goal->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="new-milestone">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin([ 'id' => 'milestone-form']); ?> 
            <?= $form->field($milestone, 'type')->dropDownList(GoalMilestone::getTypes()) ?>
            <?= $form->field($milestone, 'description') ?>
            <?=
            $form->field($milestone, 'date')->widget(DatePicker::classname(), [ 'options' => ['placeholder' => \Yii::t('goal', 'Select milestone date ...')],
                'value' => $milestone->date,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true, 'format' => 'yyyy/mm/dd'
                ]
            ])
            ?>
            <?= $form->field($milestone, 'evidence') ?>
            <?= $form->field($milestone, 'celebration') ?>
            <?= Html::submitButton(Yii::t('app', 'Save'), [ 'class' => 'btn btn-primary']); ?>
            <?php ActiveForm::end(); ?>    
        </div>
    </div>
</div>