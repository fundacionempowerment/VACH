<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['/admin/payment']];
$this->params['breadcrumbs'][] = $this->title;

$availablePaymentProvider = new ActiveDataProvider([
    'query' => $model->getAvailablePayments(),
    'pagination' => [
        'pageSize' => 10000,
    ],
        ]);
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'payment-form',]); ?>
    <?= $form->field($model, 'stamp') ?>
    <?= Yii::t('payment', 'Available payments') ?>
    <?=
    GridView::widget([
        'id' => 'grid',
        'dataProvider' => $availablePaymentProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index) {
                    return [
                        "value" => $model->id,
                        "checked" => true,
                        "onclick" => "updateTotals()",
                    ];
                },
            ],
            'id',
            'concept',
            [
                'label' => Yii::t('payment', 'Local amount'),
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['localAmount'], 2);
                },
            ],
            [
                'attribute' => 'commision',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data['commision_currency'] ?: 'ARS' . ' ' . Yii::$app->formatter->asDecimal($data['commision'] ?: 0, 2);
                },
            ],
            [
                'label' => Yii::t('payment', 'Net amount'),
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data['commision_currency'] ?: 'ARS' . ' ' . Yii::$app->formatter->asDecimal($data['netAmount'] ?: 0, 2);
                },
            ],
            [
                'label' => Yii::$app->params['part1_name'],
                'value' => function ($data) {
                    return 'ARS' . ' ' . Yii::$app->formatter->asDecimal($data['part1Amount'] ?: 0, 2);
                },
            ],
            [
                'label' => Yii::$app->params['part2_name'],
                'value' => function ($data) {
                    return 'ARS' . ' ' . Yii::$app->formatter->asDecimal($data['part2Amount'] ?: 0, 2);
                },
            ],
            'stamp:datetime',
        ]
    ]);
    ?>
    <div class="row alert alert-info">
        <div class="col-md-4">
            <p>
                <label><?= $model->getAttributeLabel('raw_amount') ?>:</label>
                <span id ="raw_amount"><?= $model->raw_amount ?></span>
            </p>
            <p>
                <label><?= $model->getAttributeLabel('commision') ?>:</label>
                <span id ="commision"><?= $model->commision ?></span>
            </p>
            <p>
                <label><?= $model->getAttributeLabel('net_amount') ?>:</label>
                <span id ="net_amount"><?= $model->raw_amount ?></span>
            </p>
        </div>
        <div class="col-md-4">
            <p>
                <label><?= $model->getAttributeLabel('part1_amount') ?>:</label>
                <span id="part1_amount"><?= $model->part1_amount ?></span>
            </p>
            <p>
                <label><?= $model->getAttributeLabel('part2_amount') ?>:</label>
                <span id ="part2_amount"><?= $model->part2_amount ?></span>
            </p>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary'), 'name' => 'save-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function updateTotals() {
        var ids = $('#grid').yiiGridView('getSelectedRows');
        $.ajax({
            url: 'index.php?r=admin/liquidation/calculate-totals',
            type: 'post',
            data: {
                ids: ids,
            },
        }).done(function (data) {
            $('#raw_amount').text(data.raw_amount);
            $('#commision').text(data.commision);
            $('#net_amount').text(data.net_amount);
            $('#part1_amount').text(data.part1_amount);
            $('#part2_amount').text(data.part2_amount);
        });
    }
</script>
