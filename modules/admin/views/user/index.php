<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $user,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->fullname, Url::to(['/user/edit', 'id' => $data['id'],]));
                },
            ],
            'username',
            'email',
            'phone',
            ['class' => 'app\components\grid\ActionColumn',
                'template' => '{delete}',
                'options' => ['width' => '40px'],
                'urlCreator' => function( $action, $data, $key, $index ) {
                    switch ($action) {
                        case 'delete' : return Url::to(['/user/delete', 'id' => $data['id'],]);
                    };
                },
            ],
        ],
    ]);
    ?>
    <?= Html::a(Yii::t('user', 'New user'), Url::to(['user/new']), ['class' => 'btn btn-success']) ?>
</div>

