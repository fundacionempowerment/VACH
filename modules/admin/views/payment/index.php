<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
$this->title = Yii::t('payment', 'Payments');

$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => $models,
    'pagination' => [
        'pageSize' => 20,
    ],
        ]);
$columns = [
    'id',
    [
        'attribute' => 'coach.fullname',
        'label' => Yii::t('app', 'Coach')
    ],
    'concept',
    [
        'attribute' => 'amount',
        'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
        'value' => function ($data) {
            return $data['currency'] . ' ' . $data['amount'];
        },
    ],
    'rate:decimal',
    [
        'label' => Yii::t('payment', 'Local amount'),
        'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
        'value' => function ($data) {
            return 'ARS ' . Yii::$app->formatter->asDecimal($data['localAmount'], 2);
        },
    ],
    [
        'attribute' => 'commision',
        'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
        'value' => function ($data) {
            return $data['commision_currency'] . ' ' . Yii::$app->formatter->asDecimal($data['commision'], 2);
        },
    ],
    [
        'label' => Yii::t('payment', 'Net amount'),
        'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
        'value' => function ($data) {
            return $data['commision_currency'] . ' ' . Yii::$app->formatter->asDecimal($data['netAmount'], 2);
        },
    ],
    'stamp:datetime',
    'statusName',
    'is_manual:boolean',
    [
        'attribute' => 'creator.fullname',
        'label' => Yii::t('app', 'Creator')
    ],
    ['class' => 'app\components\grid\ActionColumn',
        'template' => '{view} {update}',
        'options' => ['width' => '110px'],
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return Html::a(app\components\Icons::EYE, Url::to(['payment/view', 'id' => $model['id']]), [
                            'title' => Yii::t('app', 'View'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-default',
                ]);
            },
            'update' => function ($url, $model, $key) {
                return Html::a(app\components\Icons::EURO, Url::to(['payment/edit', 'id' => $model['id']]), [
                            'title' => Yii::t('payment', 'Set commision'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-default',
                ]);
            },
        ]
    ]
];
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('payment', 'Pay pendings'), Url::to(['payment/pay-pendings']), ['class' => 'btn btn-success']) ?>
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
