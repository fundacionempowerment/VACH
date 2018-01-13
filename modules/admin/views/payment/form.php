<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['/admin/payment']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'concept',
            'amount:currency',
            'rate:decimal',
            [
                'label' => Yii::t('payment', 'Local amount'),
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return 'ARS ' . Yii::$app->formatter->asDecimal($data['localAmount']);
                },
            ],
        ],
    ])
    ?>

    <?php $form = ActiveForm::begin(['id' => 'payment-form',]); ?>
    <?= $form->field($model, 'commision') ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary'), 'name' => 'save-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
