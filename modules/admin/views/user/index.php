<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a(Yii::t('user', 'New User'), Url::to(['user/new']), ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('user', 'Import Users'), Url::to(['user/import']), ['class' => 'btn btn-info']) ?>
    <?= Html::a(Yii::t('user', 'Fuse Users'), Url::to(['user/fuse']), ['class' => 'btn btn-danger']) ?>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $users,
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filter,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->fullname;
                },
            ],
            'username',
            'email',
            'phone',
            'is_administrator:boolean',
            ['class' => 'app\components\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('user', 'New user'), Url::to(['user/new']), ['class' => 'btn btn-success']) ?>
</div>

