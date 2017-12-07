<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\Product */

$this->title = ($product->id == 0 ? Yii::t('stock', 'New Licence Type') : Yii::t('app', 'Edit') . ' ' . $product->name );
$this->params['breadcrumbs'][] = ['label' => Yii::t('stock', 'Licence Types'), 'url' => ['index']];
if ($product->id != 0) {
    $this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $form = ActiveForm::begin([
                'id' => 'newproduct-form',
    ]);
    ?>

    <?= $form->field($product, 'name') ?>
    <?= $form->field($product, 'description')->textarea() ?>
    <?= $form->field($product, 'price') ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn ' . ($product->isNewRecord ? 'btn-success' : 'btn-primary'), 'name' => 'save-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
