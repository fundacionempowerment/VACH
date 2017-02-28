<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('account', 'My Money');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-companies">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $models,
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'stamp:datetime',
            'concept',
            'amount:number',
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{view}',
                'options' => ['width' => '60px'],
                'urlCreator' => function( $action, $model, $key, $index ) {
                    switch ($action) {
                        case 'view' : return Url::to(['view', 'id' => $model['id']]);
                    };
                }
            ]
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('company', 'Add money'), Url::to(['add']), ['class' => 'btn btn-success']) ?>
</div>
