<?php

use app\components\SpinnerAnchor;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('company', 'Companies');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $companies,
    'sort' => false,
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= SpinnerAnchor::widget([
            'caption' => Yii::t('company', 'New company'),
            'url' => Url::to(['company/new']),
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
                    return Html::a($data['name'], Url::to(['company/edit', 'id' => $data['id']]));
                },
            ],
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{delete}',
                'options' => ['width' => '60px'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    switch ($action) {
                        case 'delete' :
                            return Url::to(['company/delete', 'id' => $model['id'], 'delete' => '1']);
                    };
                }
            ]
        ],
    ]);
    ?>
    <p>
        <?= SpinnerAnchor::widget([
            'caption' => Yii::t('company', 'New company'),
            'url' => Url::to(['company/new']),
            'options' => ['class' => 'btn btn-success'],
        ]) ?>
    </p>
</div>
