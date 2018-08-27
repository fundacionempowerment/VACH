<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\slider\Slider;
use app\models\Stock;
use yii\web\View;

$this->title = Yii::t('stock', 'Buy Licences');

?>
<div class="col-md-12">
    <h1><?= $this->title ?></h1>
    <?php
    $form = ActiveForm::begin([
                'id' => 'buy-form',
    ]);
    ?>
    <div class="text-center">
        <div class="col-sm-12">
            Su stock de licencias actual: <?= Yii::$app->formatter->asDecimal(Stock::getStock(1)) ?> licencias
            <hr>
        </div>
        <div class="col-sm-12">
            Cantidad a comprar: <?= $model->quantity ?><br><br>
        </div>
        <div class="col-sm-12">
            Precio por licencia: <?= Yii::$app->formatter->asCurrency($model->price) ?><br><br>
        </div>
        <div class="col-sm-12">
            Total:  <b><span id="total"><?= Yii::$app->formatter->asCurrency($model->price * $model->quantity) ?></span></b><br/><br>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-push-4 col-sm-4">
            <?= Html::submitButton(\Yii::t('stock', 'Begin payment'), ['class' => 'btn btn-lg btn-success', 'name' => 'pay-button']) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-push-4 col-sm-4">
            <br/>
            <?= \Yii::t('app', 'or') ?>
            <br/>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-push-4 col-sm-4">
            <?= $form->field($model, 'payerEmail') ?>
            <?= Html::submitButton(\Yii::t('stock', 'Send payment link'), ['class' => 'btn btn-primary', 'name' => 'pay-button', 'value' => 'send']) ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?= $form->field($model, 'product_id')->hiddenInput()->label('') ?>
    <?php ActiveForm::end(); ?>
</div>
<img src="images/red-loading.gif" style="opacity: 0.01;" />
