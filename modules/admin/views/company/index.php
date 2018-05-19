<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('company', 'Companies');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $companies,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data['name'], Url::to(['company/edit', 'id' => $data['id']]));
                },
            ],
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{delete}',
                'options' => ['width' => '60px'],
                'urlCreator' => function( $action, $model, $key, $index ) {
                    switch ($action) {
                        case 'delete' : return Url::to(['company/delete', 'id' => $model['id'], 'delete' => '1']);
                    };
                }
            ]
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('company', 'New company'), Url::to(['company/new']), ['class' => 'btn btn-success']) ?>
</div>
