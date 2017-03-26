<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Product;
use app\models\User;
use yii\web\View;
use kartik\widgets\Select2;

$this->title = Yii::t('stock', 'Add licences');

$this->registerJs("function updateAmount(quantity) {
    var price = $('#addmodel-price').val();
    var quantity = $('#addmodel-quantity').val();
    var total = quantity * price;
    $('#total').text('" . Yii::$app->formatter->numberFormatterSymbols[NumberFormatter::CURRENCY_SYMBOL] . "' + total.toFixed(2));
    return true;
    }", View::POS_END, 'refresh-page');
?>
<div class="col-md-12">
    <h1><?= $this->title ?></h1>
    <?php
    $form = ActiveForm::begin([
                'id' => 'add-form',
    ]);
    ?>
    <?= $form->field($model, 'coach_id')->widget(Select2::classname(), ['data' => User::getList(),]) ?>
    <?= $form->field($model, 'product_id')->dropDownList(Product::getList()) ?>
    <?=
    $form->field($model, 'price', ['options' => [
            'onchange' => "updateAmount();"]
    ])
    ?>
    <?=
    $form->field($model, 'quantity', ['options' => [
            'onchange' => "updateAmount();"]
    ])
    ?>
    <div class="form-group">
        Total:  <b><span id="total"><?= Yii::$app->formatter->asCurrency($model->price * $model->quantity) ?></span></b><br/><br>
            <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'name' => 'pay-button']) ?>         
    </div>
    <?php ActiveForm::end(); ?>
</div>
