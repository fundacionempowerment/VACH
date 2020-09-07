<?php

use app\models\Wheel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\components\SpinnerAnchor;

/* @var $this yii\web\View */
$this->title = Yii::t('team', 'Wheels');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $wheels,
    'sort' => false,
    'pagination' => [
        'pageSize' => 20,
    ],
]);

$teamList = \app\models\Team::getList();
$personList = \app\models\Person::getList();
?>
<div class="coach-persons">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'team.company.name',
                'label' => Yii::t('company', 'Company'),

            ],
            [
                'attribute' => 'team_id',
                'label' => Yii::t('team', 'Team'),
                'format' => 'html',
                'value' => function ($data) {
                    return $data->team->name;
                },
                'filter' => $teamList,
            ],
            [
                'attribute' => 'observer_id',
                'label' => Yii::t('wheel', 'Observer'),
                'format' => 'html',
                'value' => function ($data) {
                    return $data->observer->fullname;
                },
                'filter' => $personList,
            ],
            [
                'attribute' => 'observed_id',
                'label' => Yii::t('wheel', 'Observed'),
                'format' => 'html',
                'value' => function ($data) {
                    return $data->observed->fullname;
                },
                'filter' => $personList,
            ],
            [
                'attribute' => 'type',
                'label' => Yii::t('app', 'Type'),
                'format' => 'html',
                'value' => function ($data) {
                    return Wheel::getWheelTypes()[$data->type];
                },
                'filter' => Wheel::getWheelTypes(),
            ],
            [
                'attribute' => 'progress',
                'label' => Yii::t('app', 'Progress')
            ],
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{view} {delete}',
                'options' => ['width' => '60px'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(app\components\Icons::EYE, Url::to(['wheel/manual-form', 'id' => $model['id']]), [
                            'title' => Yii::t('app', 'View'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-default',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(app\components\Icons::REDO, Url::to(['wheel/redo', 'id' => $model['id']]), [
                            'title' => Yii::t('app', 'Redo'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-danger',
                        ]);
                    },
                ]
            ]
        ],
    ]);
    ?>
</div>
