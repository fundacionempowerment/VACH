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
<div class="clearfix"></div>
<script type="text/javascript">
    function lockAndSubmit(form) {
        form.submit();
    }
</script>
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
    <div class="form-group">
        <?=
        $form->field($filter, 'companyId')->widget(Select2::classname(), [
            'data' => $companies,
            'hideSearch' => true,
            'options' => ['placeholder' => Yii::t('dashboard', 'Select company ...'), 'onchange' => 'lockAndSubmit(this.form);'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])
        ?>
        <?=
        $form->field($filter, 'teamId')->widget(Select2::classname(), [
            'data' => $teams,
            'hideSearch' => true,
            'options' => ['placeholder' => Yii::t('dashboard', 'Select team ...'), 'onchange' => 'lockAndSubmit(this.form);',],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])
        ?>
        <?=
        $form->field($filter, 'assessmentId')->widget(Select2::classname(), [
            'data' => $assessments,
            'hideSearch' => true,
            'options' => ['placeholder' => Yii::t('dashboard', 'Select assesment ...'), 'onchange' => 'lockAndSubmit(this.form);',],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])
        ?>
    </div>
    <div class="form-group">
        <?=
        $form->field($filter, 'memberId')->widget(Select2::classname(), [
            'data' => $members,
            'hideSearch' => true,
            'options' => ['placeholder' => Yii::t('dashboard', 'Select member ...'), 'onchange' => 'lockAndSubmit(this.form);',],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])
        ?>
        <?=
        $form->field($filter, 'wheelType')->widget(Select2::classname(), [
            'data' => Wheel::getWheelTypes(),
            'hideSearch' => true,
            'options' => ['placeholder' => Yii::t('dashboard', 'Select wheel type ...'), 'onchange' => "lockAndSubmit(this.form);",],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
