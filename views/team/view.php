<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $team->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'My Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row col-md-6">
        <h3><?= Yii::t('team', 'Team data') ?> </h3>
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($team->coach->fullname) ?><br />
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($team->company->name) ?><br />
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($team->sponsor->fullname) ?>
        </p>
    </div>
    <div class="row col-md-6">
        <h3><?= Yii::t('team', 'Members') ?></h3>
        <?php
        $membersDataProvider = new ArrayDataProvider([
            'allModels' => $team->members,
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
                    'format' => 'html',
                    'value' => function ($data) {
                        return Html::a($data->member->fullname, Url::to(['team/view-member', 'id' => $data['id']]));
                    },
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'options' => ['width' => '60px'],
                    'urlCreator' => function( $action, $model, $key, $index ) {
                        switch ($action) {
                            case 'update':return Url::to(['team/edit-member', 'id' => $model['id']]);
                            case 'delete' : return Url::to(['team/delete-member', 'id' => $model['id']]);
                        };
                    }
                ]
            ],
        ]);
        ?>
        <?php $form = ActiveForm::begin([ 'id' => 'addmember-form', 'options' => [ 'class' => 'form-inline']]);
        ?>
        <?= Html::a(Yii::t('team', 'New member'), Url::to(['team/new-member', 'id' => $team->id]), ['class' => 'btn btn-primary']) ?>
        <?=
        Select2::widget([
            'name' => 'new_member',
            'data' => $coachees,
            'options' => [
                'placeholder' => Yii::t('team', 'Select new member ...'),
                'style' => 'width: 220px;',
            ],
        ])
        ?>

        <?= Html::submitButton(\Yii::t('app', 'Add'), ['class' => 'btn btn-primary', 'name' => 'save-button', 'style' => 'display: inline;']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>