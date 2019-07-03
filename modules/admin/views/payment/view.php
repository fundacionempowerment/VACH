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
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['/admin/payment']];
$this->params['breadcrumbs'][] = $this->title;

$detailProvider = new ArrayDataProvider([
    'allModels' => $model->logs,
        ]);

$transactionProvider = new ArrayDataProvider([
    'allModels' => $model->transactions,
]);
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!$model->liquidation_id) { ?>
        <p>
            <?= Html::a(Yii::t('payment', 'Set commision'), Url::to(['payment/edit', 'id' => $model->id]), ['class' => 'btn btn-default']) ?>
        </p>
    <?php } ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'uuid',
            'stamp:datetime',
            'concept',
            'amount:currency',
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
                    return $data ? $data['commision_currency'] . ' ' . $data['commision'] : '';
                },
            ],
            [
                'label' => Yii::t('payment', 'Net amount'),
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data['commision_currency'] . ' ' . $data['netAmount'];
                },
            ],
            [
                'label' => Yii::$app->params['part1_name'],
                'value' => function ($data) {
                    return 'ARS' . ' ' . Yii::$app->formatter->asDecimal($data['part1Amount'] ?: 0, 2);
                },
            ],
            [
                'label' => Yii::$app->params['part2_name'],
                'value' => function ($data) {
                    return 'ARS' . ' ' . Yii::$app->formatter->asDecimal($data['part2Amount'] ?: 0, 2);
                },
            ],
            'statusName',
            'is_manual:boolean',
        ],
    ])
    ?>

    <h2><?= $model->getAttributeLabel('log') ?></h2>
    <?=
    GridView::widget([
        'dataProvider' => $detailProvider,
        'columns' => [
            'stamp:datetime',
            'statusName',
        ],
    ]);
    ?>

    <h2><?= $model->getAttributeLabel('transactions') ?></h2>
    <?=
    GridView::widget([
        'dataProvider' => $transactionProvider,
        'columns' => [
            'stamp:datetime',
            'statusName',
        ],
    ]);
    ?>
</div>
