<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\slider\Slider;
use app\models\Stock;
use yii\web\View;

$this->title = Yii::t('stock', 'Buy licences');

$this->registerJs("function setQuantity(quantity) { $('#buymodel-quantity').val(quantity); return true; }", View::POS_END, 'refresh-page');
?>
<div class="col-md-12">
    <h1><?= $this->title ?></h1>
    <p>
        Ingrese la cantidad de licencia que desea comprar:
    </p>
    <?php
    $form = ActiveForm::begin([
                'id' => 'buy-form',
    ]);
    ?>
    <div class="text-center">
        <?= $form->field($model, 'quantity')->hiddenInput()->label('') ?>
        <div class="col-sm-push-0 col-sm-12">
            <div class="btn-group btn-group-md" data-toggle="buttons">
                <?php for ($n = 1; $n <= 20; $n++) { ?>
                    <label class="btn btn-default <?= $model->quantity == $n ? 'active' : '' ?>" onclick="setQuantity(<?= $n ?>);">
                        <input type="radio" value="<?= $n ?>" <?= $model->quantity == $n ? 'checked' : '' ?>><?= $n ?>
                    </label>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-push-4 col-sm-4">
            <?= $form->field($model, 'product_id')->hiddenInput()->label('') ?>
            <?= Html::submitButton(\Yii::t('stock', 'Begin payment'), ['class' => 'btn btn-lg btn-success', 'name' => 'pay-button']) ?>         
        </div>
        <div class="clearfix"></div>
        <div class="text-center">
            <br/><br/>
            Su stock de licencias actual: <?= Yii::$app->formatter->asDecimal(Stock::getStock(1)) ?><br/>
            Precio por licencia: <?= $model->price ?> USD<br/>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
