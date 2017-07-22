<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
$this->title = Yii::t('payment', 'My Payments');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $models,
    'pagination' => [
        'pageSize' => 10,
    ],
        ]);
$columns = [
    'uuid',
    'stamp:datetime',
    'concept',
    [
        'attribute' => 'amount',
        'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
        'value' => function ($data) {
            return $data['currency'] . ' $' . $data['amount'];
        },
    ],
    'statusName',
    ['class' => 'app\components\grid\ActionColumn',
        'template' => '{view}',
        'options' => ['width' => '60px'],
    ]
];
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
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
