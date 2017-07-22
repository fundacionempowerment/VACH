<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Stock;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
$this->title = Yii::t('stock', 'Licences');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $models,
    'pagination' => [
        'pageSize' => 10,
    ],
        ]);
$columns = [
    [
        'attribute' => 'coach_name',
        'label' => Yii::t('app', 'Coach')
    ],
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
        'label' => Yii::t('stock', 'Amount'),
        'value' => function ($data) {
            return 'USD ' . Yii::$app->formatter->asDecimal($data['amount'], 2);
        },
    ],
    [
        'attribute' => 'localAmount',
        'label' => Yii::t('stock', 'Local Amount'),
        'value' => function ($data) {
            return 'ARS ' . Yii::$app->formatter->asDecimal($data['localAmount'], 2);
        },
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
        'attribute' => 'creator_name',
        'label' => Yii::t('app', 'Creator')
    ],
    [
        'attribute' => 'consumed_stamp',
        'label' => Yii::t('stock', 'Consumed'),
    ],
];
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('stock', 'Add licences'), Url::to(['stock/add']), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('stock', 'Remove licences'), Url::to(['stock/remove']), ['class' => 'btn btn-danger']) ?>
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
