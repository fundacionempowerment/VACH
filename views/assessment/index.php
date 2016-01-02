<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('assessment', 'Assessments');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-assessments">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $assessments,
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($data) {
                    return Html::a($data->fullname, Url::to(['assessment/view', 'id' => $data['id']]));
                },
            ],
            [
                'attribute' => 'IndividualWheelStatus',
                'format' => 'html',
                'value' => function($data) {
                    return Html::a($data->IndividualWheelStatus, Url::to(['assessment/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'GroupWheelStatus',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->GroupWheelStatus, Url::to(['assessment/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'OrganizationalWheelStatus',
                'format' => 'html',
                'value' => function($data) {
                    return Html::a($data->OrganizationalWheelStatus, Url::to(['assessment/view', 'id' => $data['id'],]));
                },
            ],
            [
                'format' => 'html',
                'value' => function($data) {
                    $dashboard = Html::a(Yii::t('dashboard', 'Dashboard'), Url::to(['assessment/go-to-dashboard', 'id' => $data['id']]), ['class' => 'btn btn-default']);
                    $report =Html::a(Yii::t('report', 'Report'), Url::to(['report/view', 'id' => $data['id']]), ['class' => 'btn btn-default']);
                    return  "$dashboard $report";
                },
            ]
        ]
    ]); ?> 
</div>
