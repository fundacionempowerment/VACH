<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('user', 'Persons');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-persons">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $persons,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'fullname',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data['fullname'], Url::to(['person/edit', 'id' => $data['id'],])); // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],
            'shortname',
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{delete}',
                'options' => ['width' => '60px'],
                'urlCreator' => function( $action, $model, $key, $index ) {
                    switch ($action) {
                        case 'delete' : return Url::to(['person/delete', 'id' => $model['id'], 'delete' => '1',]);
                    };
                }
            ]
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('user', 'New person'), Url::to(['person/new']), ['class' => 'btn btn-success']) ?>
</div>
