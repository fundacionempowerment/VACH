<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Stock;

/* @var $this yii\web\View */
$this->title = Yii::t('stock', 'My Licences');

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
    <h2><?= Yii::t('stock', 'Your balance') ?>: <?= Stock::getStock(1) ?></h2>
    <p>
        <?= Yii::$app->params['manual_mode'] ? '' : Html::a(Yii::t('stock', 'Buy Licences'), Url::to(['stock/new']), ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'product.name',
            'quantity',
            [
                'attribute' => 'team',
                'value' => function($data) {
                    return $data->team ? $data->team->fullname : '';
                },
            ],
            'stamp',
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{view}',
                'options' => ['width' => '60px'],
            ]
        ],
    ]);
    ?>
</div>
