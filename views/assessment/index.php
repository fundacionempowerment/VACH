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
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->fullname, Url::to(['assessment/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'IndividualWheelStatus',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->IndividualWheelStatus, Url::to(['assessment/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'GroupWheelStatus',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->GroupWheelStatus, Url::to(['assessment/view', 'id' => $data['id'],]));
                },
            ],
            [
                'attribute' => 'OrganizationalWheelStatus',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->OrganizationalWheelStatus, Url::to(['assessment/view', 'id' => $data['id'],]));
                },
            ],
        ],
    ]);
    ?>
</div>
