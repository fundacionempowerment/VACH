<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('user', 'View coachee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row col-md-12">
        <h3><?= Yii::t('user', 'Personal data') ?></h3>
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($coachee->coach->fullname) ?><br />
            <?= Yii::t('user', 'Coachee') ?>: <?= Html::label($coachee->fullname) ?><br />
            <?= Yii::t('user', 'Email') ?>: <?= Html::label($coachee->email) ?>
        </p>
        <?= Html::a(Yii::t('user', 'Edit coachee'), Url::to(['coachee/edit', 'id' => $coachee->id]), ['class' => 'btn btn-default']) ?>
    </div>
    <div class="row col-md-12">
        <h3><?= Yii::t('wheel', 'Wheels') ?></h3>
        <?php
        $wheelDataProvider = new ArrayDataProvider([
            'allModels' => $coachee->wheels,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $wheelDataProvider,
            'summary' => '',
            'options' => ['style' => 'width: 360px;'],
            'columns' => [
                [
                    'attribute' => 'id',
                    'format' => 'html',
                    'options' => ['width' => '60px'],
                    'value' => function ($data) {
                        return Html::a($data['id'], Url::to(['/wheel', 'wheelid' => $data['id'],]));
                    },
                ],
                [
                    'attribute' => 'date',
                    'format' => 'html',
                    'options' => ['width' => '60px'],
                    'value' => function ($data) {
                        return Html::a($data['date'], Url::to(['/wheel', 'wheelid' => $data['id'],]));
                    },
                ],
                [
                    'attribute' => Yii::t('wheel', 'answers'),
                    'format' => 'html',
                    'options' => ['width' => '60px'],
                    'value' => function ($data) {
                        return Html::a(count($data->answers), Url::to(['/wheel', 'wheelid' => $data['id'],]));
                    },
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{continue} {delete}',
                    'options' => ['width' => '120px'],
                    'buttons' => [
                        'continue' => function ($url, $data, $key) {
                            return count($data->answers) == 80 ? '' : Html::a(Yii::t('wheel', 'continue...'), Url::to(['wheel/run', 'coachee_id' => $data->coachee->id, 'id' => $data->id]), ['class' => 'btn btn-success btn-xs']);
                        }
                    ],
                    'urlCreator' => function( $action, $model, $key, $index ) {
                        switch ($action) {
                            case 'delete' : return Url::to(['wheel/delete', 'id' => $model['id']]);
                        };
                    }
                ]
            ],
        ]);
        ?>
        <?= Html::a(Yii::t('wheel', 'New wheel'), Url::to(['wheel/run', 'coachee_id' => $coachee->id]), ['class' => 'btn btn-success']) ?>
    </div>

    <div class="row col-md-12">
        <h3><?= Yii::t('goal', 'Goals') ?></h3>
        <?php
        $goalDataProvider = new ArrayDataProvider([
            'allModels' => $coachee->goals,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $goalDataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'id',
                    'format' => 'html',
                    'options' => ['width' => '60px'],
                    'value' => function ($data) {
                        return Html::a($data['id'], Url::to(['/goal', 'id' => $data['id'],]));
                    },
                ],
                [
                    'attribute' => 'name',
                    'format' => 'html',
                    'value' => function ($data) {
                        return Html::a($data['name'], Url::to(['/goal', 'id' => $data['id'],]));
                    },
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'options' => ['width' => '120px'],
                    'urlCreator' => function( $action, $model, $key, $index ) {
                        switch ($action) {
                            case 'delete' : return Url::to(['goal/delete', 'id' => $model['id']]);
                        };
                    }
                ]
            ],
        ]);
        ?>
        <?= Html::a(Yii::t('goal', 'New goal'), Url::to(['goal/new', 'coachee_id' => $coachee->id]), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('goal', 'View action plan'), Url::to(['goal/plan', 'coachee_id' => $coachee->id]), ['class' => 'btn btn-primary']) ?>
    </div>
</div>
