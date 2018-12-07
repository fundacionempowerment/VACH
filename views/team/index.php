<?php

use app\components\SpinnerAnchor;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('team', 'Teams');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $teams,
    'sort' => false,
    'pagination' => [
        'pageSize' => 20,
    ],
]);
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'filter' => \app\models\TeamType::getList(),
            ],
            [
                'attribute' => 'IndividualWheelStatus',
                'format' => 'html',
                'value' => function ($data) {
                    if ($data->teamType->level_0_enabled) {
                        return Html::a($data->individualWheelStatus, Url::to(['team/view', 'id' => $data['id'],]));
                    } else {
                        return "";
                    }
                },
            ],
            [
                'attribute' => 'GroupWheelStatus',
                'format' => 'html',
                'value' => function ($data) {
                    if ($data->teamType->level_1_enabled) {
                        return Html::a($data->groupWheelStatus, Url::to(['team/view', 'id' => $data['id'],]));
                    } else {
                        return "";
                    }
                },
            ],
            [
                'attribute' => 'OrganizationalWheelStatus',
                'format' => 'html',
                'value' => function ($data) {
                    if ($data->teamType->level_2_enabled) {
                        return Html::a($data->organizationalWheelStatus, Url::to(['team/view', 'id' => $data['id'],]));
                    } else {
                        return "";
                    }
                },
            ],
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    switch ($action) {
                        case 'delete' :
                            return Url::to(['team/delete', 'id' => $model['id'], 'delete' => '1',]);
                    };
                },
                'contentOptions' => [
                    'style' => 'width:70px; text-align:center;'
                ],
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
