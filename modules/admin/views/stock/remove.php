<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Product;
use app\models\User;
use yii\web\View;
use kartik\widgets\Select2;

$this->title = Yii::t('stock', 'Remove licences');

$this->params['breadcrumbs'][] = ['label' => Yii::t('stock', 'Licences'), 'url' => ['/admin/stock']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col-md-12">
    <h1><?= $this->title ?></h1>
    <?php
    $form = ActiveForm::begin([
                'id' => 'add-form',
    ]);
    ?>
    <?= $form->field($model, 'coach_id')->widget(Select2::classname(), ['data' => User::getUserList(),]) ?>
    <?= $form->field($model, 'product_id')->dropDownList(Product::getList()) ?>
    <?=
    $form->field($model, 'quantity')
    ?>
    <div class="form-group">
        <?=
        Html::submitButton(\Yii::t('app', 'Save'), [
            'class' => 'btn btn-success',
            'name' => 'pay-button',
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
        ])
        ?>
    </div>
<?php ActiveForm::end(); ?>
</div>
