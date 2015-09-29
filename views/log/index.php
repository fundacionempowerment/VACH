<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('log', 'Event Log');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-Logs">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $logs,
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
                'attribute' => 'datetime',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'text',
            ],
        ],
    ]);
    ?>
</div>
