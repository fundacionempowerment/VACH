<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\SpinnerAnchor;

/* @var $this yii\web\View */
$this->title = Yii::t('team', 'Teams');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-teams">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= SpinnerAnchor::widget([
            'caption' => Yii::t('team', 'New team'),
            'url' => Url::to(['team/new']),
            'options' => ['class' => 'btn btn-success'],
        ]) ?>
    </p>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $teams,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->fullname, Url::to(['team/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'team_type_id',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->teamType->name;
                },
            ],
            [
                'attribute' => 'IndividualWheelStatus',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->individualWheelStatus, Url::to(['team/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'GroupWheelStatus',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->groupWheelStatus, Url::to(['team/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'OrganizationalWheelStatus',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->organizationalWheelStatus, Url::to(['team/view', 'id' => $data['id'],]));
                },
            ],
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['width' => '110px'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    switch ($action) {
                        case 'update' :
                            return Url::to(['team/edit', 'id' => $model['id']]);
                        case 'delete' :
                            return Url::to(['team/delete', 'id' => $model['id'], 'delete' => '1',]);
                    };
                }
            ]
        ],
    ]);
    ?>
    <p>
        <?= SpinnerAnchor::widget([
            'caption' => Yii::t('team', 'New team'),
            'url' => Url::to(['team/new']),
            'options' => ['class' => 'btn btn-success'],
        ]) ?>
    </p>
</div>
