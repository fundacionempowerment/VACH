<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $model->product->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('stock', 'Licences'), 'url' => ['/admin/stock']];
$this->params['breadcrumbs'][] = $this->title;

$detailProvider = new ArrayDataProvider([
    'allModels' => $model->payments,
        ]);
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product.name',
            'quantity',
            'price:currency',
            'total:currency',
            'stamp:datetime',
            'statusName',
        ],
    ])
    ?>

    <h2><?= $model->getAttributeLabel('payments') ?></h2>
    <?=
    GridView::widget([
        'dataProvider' => $detailProvider,
        'columns' => [
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
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['localAmount']);
                },
            ],
            [
                'attribute' => 'commision',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data['commision_currency'] . ' ' . $data['commision'];
                },
            ],
            [
                'label' => Yii::t('payment', 'Net amount'),
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data['commision_currency'] . ' ' . $data['netAmount'];
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
                'template' => '{view}',
                'options' => ['width' => '60px'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(app\components\Icons::EYE, Url::to(['payment/view', 'id' => $model['id']]), [
                                    'title' => Yii::t('app', 'View'),
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-default',
                        ]);
                    },
                ]
            ]
        ],
    ]);
    ?>

</div>
