<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('user', 'My Coachees');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-coachees">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $coachees,
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
                    return Html::a($data['fullname'], Url::to(['coachee/view', 'id' => $data['id'],])); // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['width' => '60px'],
                'urlCreator' => function( $action, $model, $key, $index ) {
                    switch ($action) {
                        case 'update' : return Url::to(['coachee/edit', 'id' => $model['id']]);
                        case 'delete' : return Url::to(['coachee/delete', 'id' => $model['id'], 'delete' => '1',]);
                    };
                }
            ]
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('user', 'New coachee'), Url::to(['coachee/new']), ['class' => 'btn btn-primary']) ?>
</div>
