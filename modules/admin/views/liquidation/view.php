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

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Liquidations'), 'url' => ['/admin/liquidation']];
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
            'stamp:datetime',
            [
                'label' => Yii::t('payment', 'Local amount'),
                'value' => function ($data) {
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['raw_amount'], 2);
                },
            ],
            [
                'attribute' => 'commision',
                'value' => function ($data) {
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['commision'], 2);
                },
            ],
            [
                'label' => Yii::t('payment', 'Net amount'),
                'value' => function ($data) {
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['net_amount'], 2);
                },
            ],
            [
                'attribute' => 'part1_amount',
                'value' => function ($data) {
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['part1_amount'], 2);
                },
            ],
            [
                'attribute' => 'part2_amount',
                'value' => function ($data) {
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['part2_amount'], 2);
                },
            ],
        ],
    ])
    ?>

    <h2><?= $model->getAttributeLabel('payments') ?></h2>
    <?=
    GridView::widget([
        'dataProvider' => $detailProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'coach.fullname',
                'label' => Yii::t('payment', 'Coach'),
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
            [
                'attribute' => 'creator.fullname',
                'label' => Yii::t('app', 'Creator')
            ],
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{view}',
                'options' => ['width' => '110px'],
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
