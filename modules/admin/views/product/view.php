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
/* @var $product app\models\Product */

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('stock', 'Licence Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('stock', 'Edit Licence Type'), ['edit', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
        <?=
        $product->deletable ?
                Html::a(Yii::t('team', 'Delete licence type'), ['delete', 'id' => $product->id, 'delete' => '1',], ['class' => 'btn btn-danger',
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]) : ''
        ?>
    </p>
    <?=
    DetailView::widget([
        'model' => $product,
        'attributes' => [
            'name',
            'description',
            'price:currency',
        ],
    ])
    ?>
</div>

