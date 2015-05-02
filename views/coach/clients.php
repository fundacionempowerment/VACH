<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('user','Clients');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-clients">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $clients,
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
                    return Html::a($data['fullname'], Url::to(['/wheel', 'clientid' => $data['id'],])); // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'urlCreator' => function( $action, $model, $key, $index ) {
                    switch ($action) {
                        case 'update' : return Url::to(['client', 'id' => $model['id'],]);
                        case 'delete' : return Url::to(['client', 'id' => $model['id'], 'delete' => '1',]);
                    };
                }
            ]
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('user','New client'), Url::to(['coach/newclient']), ['class' => 'btn btn-primary']) ?>
</div>
