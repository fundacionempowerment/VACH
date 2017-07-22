<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Stock;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
$this->title = Yii::t('stock', 'My Licences');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $models,
    'pagination' => [
        'pageSize' => 10,
    ],
        ]);
$columns = [
    [
        'attribute' => 'status',
        'label' => Yii::t('app', 'Status'),
        'value' => function ($data) {
            return Stock::getStatusList()[$data['status']];
        },
    ],
    [
        'attribute' => 'quantity',
        'label' => Yii::t('stock', 'Quantity')
    ],
    [
        'attribute' => 'price',
        'label' => Yii::t('stock', 'Price'),
        'format' => 'currency',
    ],
    [
        'attribute' => 'amount',
        'label' => Yii::t('payment', 'Amount'),
        'format' => 'currency',
    ],
    [
        'attribute' => 'company_name',
        'label' => Yii::t('company', 'Company')
    ],
    [
        'attribute' => 'team_name',
        'label' => Yii::t('team', 'Team')
    ],
    [
        'attribute' => 'created_stamp',
        'label' => Yii::t('stock', 'Created'),
    ],
    [
        'attribute' => 'consumed_stamp',
        'label' => Yii::t('stock', 'Consumed'),
    ],
];
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= Yii::t('stock', 'Your balance') ?>: <?= Stock::getStock(1) ?></h2>
    <p>
        <?= Yii::$app->params['manual_mode'] ? '' : Html::a(Yii::t('stock', 'Buy Licences'), Url::to(['stock/new']), ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'exportConfig' => [
            ExportMenu::FORMAT_PDF => false,
        ],
        'fontAwesome' => true,
        'dropdownOptions' => [
            'label' => 'Export All',
            'class' => 'btn btn-default'
        ]
    ])
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]);
    ?>
</div>
