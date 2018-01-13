<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('feedback', 'Feedbacks');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-feedbacks">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $feedbacks,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
            ],
            [
                'attribute' => 'effectiveness',
            ],
            [
                'attribute' => 'efficience',
            ],
            [
                'attribute' => 'satisfaction',
            ],
            [
                'attribute' => 'comment',
            ],
            [
                'attribute' => 'datetime',
            ],
        ],
    ]);
    ?>
</div>
