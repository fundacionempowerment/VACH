<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('stock', 'Licence Types');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-products">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $products,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->name, Url::to(['product/view', 'id' => $data['id'],]));
                },
            ],
            'price:currency',
            [
                'class' => 'app\components\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['width' => '110px'],
                'urlCreator' => function( $action, $model, $key, $index ) {
                    switch ($action) {
                        case 'update' : return Url::to(['product/edit', 'id' => $model['id']]);
                        case 'delete' : return Url::to(['product/delete', 'id' => $model['id'], 'delete' => '1',]);
                    };
                }
            ]
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('stock', 'New Licence Type'), Url::to(['product/new']), ['class' => 'btn btn-success']) ?>
</div>
