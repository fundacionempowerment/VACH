<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Stock;

/* @var $this yii\web\View */
$this->title = Yii::t('stock', 'Licences');

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
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'product.name',
            'quantity',
            'price:currency',
            'total:currency',
            'stamp:datetime',
            'statusName',
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{view}',
                'options' => ['width' => '60px'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(app\components\Icons::EYE, Url::to(['stock/view', 'id' => $model['id']]), [
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
