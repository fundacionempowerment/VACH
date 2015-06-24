<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;
use app\models\Wheel;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
?>
<div class="col-lg-12">
    <?php
    $form = ActiveForm::begin([
                'id' => 'dashboard-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-3',
                        'offset' => '',
                        'wrapper' => '',
                        'error' => '',
                        'hint' => '',
                    ],
                    'options' => ['class' => 'col-md-6']
                ],
    ]);
    ?>
    <?=
    $form->field($filter, 'companyId')->widget(Select2::classname(), [
        'data' => $companies,
        'options' => ['placeholder' => Yii::t('dashboard', 'Select company ...'), 'onchange' => 'this.form.submit()'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])
    ?>
    <?=
    $form->field($filter, 'teamId')->widget(Select2::classname(), [
        'data' => $teams,
        'options' => ['placeholder' => Yii::t('dashboard', 'Select team ...'), 'onchange' => 'this.form.submit()',],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])
    ?>
    <?=
    $form->field($filter, 'assessmentId')->widget(Select2::classname(), [
        'data' => $assessments,
        'options' => ['placeholder' => Yii::t('dashboard', 'Select assesment ...'), 'onchange' => 'this.form.submit()',],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])
    ?>
    <?=
    $form->field($filter, 'memberId')->widget(Select2::classname(), [
        'data' => $members,
        'options' => ['placeholder' => Yii::t('dashboard', 'Select member ...'), 'onchange' => 'this.form.submit()',],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])
    ?>
    <?=
    $form->field($filter, 'wheelType')->widget(Select2::classname(), [
        'data' => Wheel::getWheelTypes(),
        'options' => ['placeholder' => Yii::t('dashboard', 'Select wheel type ...'), 'onchange' => 'this.form.submit()',],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])
    ?>
    <?php ActiveForm::end(); ?>
</div>
