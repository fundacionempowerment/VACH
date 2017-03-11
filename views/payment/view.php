<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $model->concept . ' - ' . Yii::$app->formatter->asDatetime($model->stamp);
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'My Payments'), 'url' => ['/payment']];
$this->params['breadcrumbs'][] = $this->title;

$detailProvider = new ArrayDataProvider([
    'allModels' => $model->logs,
        ]);
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'stamp:datetime',
            'concept',
            'amount:currency',
            'statusName',
        ],
    ])
    ?>

    <h2><?= $model->getAttributeLabel('log') ?></h2>
    <?=
    GridView::widget([
        'dataProvider' => $detailProvider,
        'columns' => [
            'stamp:datetime',
            'statusName',
        ],
    ]);
    ?>

</div>
