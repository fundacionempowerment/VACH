<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $assessment->id == 0 ? Yii::t('assessment', 'New assessment') : $assessment->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = $this->title;

$lock_button = Yii::$app->params['monetize'] && $licences_diff > 0;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-12">
        <h4><?= Yii::t('team', 'Team data') ?> </h4>
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?><br />
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($assessment->team->company->name) ?><br />
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($assessment->team->sponsor->fullname) ?>
        </p>
    </div>
    <div class="row col-md-12">
        <h4><?= Yii::t('team', 'Members') ?></h4>
        <?php
        $membersDataProvider = new ArrayDataProvider([
            'allModels' => $assessment->team->members,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $membersDataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'member.fullname',
                ],
            ],
        ]);
        ?>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'newassessment-form',
        ]);
        ?>
    </div>
    <div class="row col-md-12" style="margin-right: 0px;">
        <h4><?= Yii::t('assessment', 'Assessment') ?></h4>
        <?= $form->field($assessment, 'name') ?>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary ', 'disabled' => $lock_button, 'name' => 'save-button']) ?>
            <?php if ($lock_button) { ?>
                <?= Yii::t('assessment', 'You need {count} more licence', ['count' => $licences_diff]) ?>
                <?= Html::a(\Yii::t('stock', 'Buy Licences'), ['/stock/new', 'id' => 1, 'quantity' => $licences_diff], ['class' => 'btn btn-success', 'name' => 'buy-button']) ?>
            <?php } ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
