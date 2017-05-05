<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('payment', 'Liquidations');

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
    <p>
        <?= Html::a(Yii::t('payment', 'Create liquidation'), Url::to(['liquidation/new']), ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
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
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{view}',
            ]
        ],
    ]);
    ?>
</div>
