<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('payment', 'Pay pendings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['/admin/payment']];
$this->params['breadcrumbs'][] = $this->title;

$provider = new ActiveDataProvider([
    'query' => $models,
    'pagination' => false,
        ]);
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'payment-form',]); ?>
    <?=
    GridView::widget([
        'id' => 'grid',
        'dataProvider' => $provider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index) {
                    return [
                        "value" => $model->id,
                        "checked" => false,
                        "onclick" => "updateTotals()",
                    ];
                },
            ],
            'id',
            [
                'attribute' => 'coach.fullname',
                'label' => Yii::t('app', 'Coach')
            ],
            'concept',
            [
                'attribute' => 'amount',
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data['currency'] . ' ' . $data['amount'];
                },
            ],
            'rate:decimal',
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
                    return $data['commision_currency'] . ' ' . Yii::$app->formatter->asDecimal($data['commision'], 2);
                },
            ],
            [
                'label' => Yii::t('payment', 'Net amount'),
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data['commision_currency'] . ' ' . Yii::$app->formatter->asDecimal($data['netAmount'], 2);
                },
            ],
            'stamp:datetime',
            'statusName',
            'is_manual:boolean',
            [
                'attribute' => 'creator.fullname',
                'label' => Yii::t('app', 'Creator')
            ],
        ]
    ]);
    ?>
    <div class="row alert alert-info">
        <div class="col-md-4">
            <p>
                <label><?= Yii::t('app', 'Total') ?>:</label>
                <span id ="total"></span>
            </p>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <?=
        Html::submitButton(\Yii::t('payment', 'Mark as payed'), [
            'class' => 'btn btn-success',
            'name' => 'save-button',
            'data-confirm' => Yii::t('app', 'Are you sure?'),
        ])
        ?>
    </div>
<?php ActiveForm::end(); ?>
</div>
<script>
    function updateTotals() {
        var ids = $('#grid').yiiGridView('getSelectedRows');
        $.ajax({
            url: 'index.php?r=admin/payment/calculate-totals',
            type: 'post',
            data: {
                ids: ids,
            },
        }).done(function (data) {
            $('#total').text(data.total);
        });
    }
</script>
