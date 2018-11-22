<?php

use app\models\Stock;
use kartik\export\ExportMenu;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('stock', 'My Licences');

$this->params['breadcrumbs'][] = $this->title;

$availableDataProvider = new ActiveDataProvider([
    'query' => $availableModels,
    'pagination' => [
        'pageSize' => 20,
    ],
]);
$othersDataProvider = new ActiveDataProvider([
    'query' => $othersModels,
    'pagination' => [
        'pageSize' => 20,
    ],
]);

$availableColumns = [
    [
        'attribute' => 'product_name',
        'label' => Yii::t('app', 'Type'),
        'value' => function ($data) {
            return Html::tag('strong', $data['product_name']);
        },
        'format' => 'html',
    ],
    [
        'attribute' => 'quantity',
        'label' => Yii::t('stock', 'Quantity'),
        'value' => function ($data) {
            return Html::tag('strong', $data['quantity']);
        },
        'format' => 'html',
    ],
    [
        'attribute' => 'price',
        'label' => Yii::t('stock', 'Price'),
        'value' => function ($data) {
            return 'USD ' . Yii::$app->formatter->asDecimal($data['price'], 2);
        },
    ],
    [
        'attribute' => 'amount',
        'label' => Yii::t('payment', 'Amount'),
        'value' => function ($data) {
            return 'USD ' . Yii::$app->formatter->asDecimal($data['amount'], 2);
        },
    ],
    [
        'attribute' => 'rate',
        'label' => Yii::t('payment', 'Rate'),
        'value' => function ($data) {
            return Yii::$app->formatter->asDecimal($data['rate'], 2);
        },
    ],
    [
        'attribute' => 'created_stamp',
        'label' => Yii::t('stock', 'Created'),
        'format' => 'date',
    ],
    [
        'label' => Yii::t('stock', 'Payed'),
        'value' => function ($data) {
            if ($data['payment_status'] == \app\models\Payment::STATUS_PENDING && $data['price'] > 0) {
                return Html::a(Yii::t('payment', 'Pay'), ['payment/select', 'referenceCode' => $data['payment_uuid']], ['class' => 'btn btn-success']);
            } else if ($data['payment_status'] == \app\models\Payment::STATUS_PAID) {
                return Yii::$app->formatter->asDate($data['payment_date']);
            }
            return '';
        },
        'format' => 'html',
    ],
];

$consumedColumns = [
    [
        'attribute' => 'product_name',
        'label' => Yii::t('app', 'Type'),
    ],
    [
        'attribute' => 'stock_status',
        'label' => Yii::t('app', 'Status'),
        'value' => function ($data) {
            return Stock::getStatusList()[$data['stock_status']];
        },
    ],
    [
        'attribute' => 'quantity',
        'label' => Yii::t('stock', 'Quantity')
    ],
    [
        'attribute' => 'price',
        'label' => Yii::t('stock', 'Price'),
        'value' => function ($data) {
            return 'USD ' . Yii::$app->formatter->asDecimal($data['price'], 2);
        },
    ],
    [
        'attribute' => 'amount',
        'label' => Yii::t('payment', 'Amount'),
        'value' => function ($data) {
            return 'USD ' . Yii::$app->formatter->asDecimal($data['amount'], 2);
        },
    ],
    [
        'attribute' => 'rate',
        'label' => Yii::t('payment', 'Rate'),
        'value' => function ($data) {
            return Yii::$app->formatter->asDecimal($data['rate'], 2);
        },
    ],
    [
        'attribute' => 'created_stamp',
        'label' => Yii::t('stock', 'Created'),
        'format' => 'date',
    ],
    [
        'label' => Yii::t('stock', 'Payed'),
        'value' => function ($data) {
            if ($data['payment_status'] == \app\models\Payment::STATUS_PENDING && $data['price'] > 0) {
                return Html::a(Yii::t('payment', 'Pay'), ['payment/select', 'referenceCode' => $data['payment_uuid']], ['class' => 'btn btn-success']);
            } else if ($data['payment_status'] == \app\models\Payment::STATUS_PAID) {
                return Yii::$app->formatter->asDate($data['payment_date']);
            }
            return '';
        },
        'format' => 'html',
    ],
];
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= Yii::t('stock', 'Available licences') ?></h2>
    <?=
    ExportMenu::widget([
        'dataProvider' => $availableDataProvider,
        'columns' => $availableColumns,
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
        'dataProvider' => $availableDataProvider,
        'columns' => $availableColumns,
    ]);
    ?>
    <p>
        <?= Yii::$app->params['manual_mode'] ? '' : Html::a(Yii::t('stock', 'Buy Licences'), Url::to(['stock/new']), ['class' => 'btn btn-success']) ?>
    </p>
    <h2><?= Yii::t('stock', 'Not available licences') ?></h2>
    <?=
    ExportMenu::widget([
        'dataProvider' => $othersDataProvider,
        'columns' => $consumedColumns,
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
        'dataProvider' => $othersDataProvider,
        'columns' => $consumedColumns,
    ]);
    ?>
</div>
