<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('payment', 'My Payments');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $models,
    'pagination' => [
        'pageSize' => 10,
    ],
        ]);
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a(Yii::t('stock', 'Buy licences'), Url::to(['stock/new']), ['class' => 'btn btn-success']) ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'stamp:datetime',
            'concept',
            'amount:currency',
            'statusName',
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{view}',
                'options' => ['width' => '60px'],
                'urlCreator' => function( $action, $model, $key, $index ) {
                    switch ($action) {
                        case 'view' : return Url::to(['view', 'id' => $model['id']]);
                    };
                }
            ]
        ],
    ]);
    ?>
</div>
