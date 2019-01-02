<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $teamType app\models\TeamType */

$this->title = $teamType->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Team Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$detailProvider = new ArrayDataProvider([
    'allModels' => $teamType->wheelQuestions,
    'pagination' => false,
        ]);
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('team', 'Edit team type'), ['edit', 'id' => $teamType->id], ['class' => 'btn btn-primary']) ?>
        <?=
        $teamType->deletable ?
                Html::a(Yii::t('team', 'Delete team type'), ['delete', 'id' => $teamType->id, 'delete' => '1',], ['class' => 'btn btn-danger',
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]) : ''
        ?>
    </p>
    <?=
    DetailView::widget([
        'model' => $teamType,
        'attributes' => [
            'name',
            [
                'attribute' => 'product',
                'value' => $teamType->product->name,
            ],
        ],
    ])
    ?>
    <h3><?= $teamType->getAttributeLabel('wheelQuestions') ?></h3>
    <?=
    GridView::widget([
        'dataProvider' => $detailProvider,
        'columns' => [
            [
                'attribute' => 'dimension',
                'value' => function ($data) {
                    return $data->teamTypeDimension->name;
                },
            ],
            [
                'attribute' => 'question',
                'value' => function ($data) {
                    return $data ? $data->question->text : '';
                },
            ],
        ],
    ]);
    ?>
</div>

