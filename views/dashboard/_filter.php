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
<script type="text/javascript">
    function lockAndSubmit(form) {
        var filter = $("#filter-div").css('opacity', 0.3);
        form.submit();
    }
</script>
<div id="filter-div" class="col-md-12">
    <?php
    $form = ActiveForm::begin([
                'id' => 'dashboard-form',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-3',
                        'offset' => '',
                        'wrapper' => '',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
    ]);
    ?>
    <div class="form-group">
        <div class="col-md-6">
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
        </div>
        <div class="col-md-6">
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
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-4">
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
        <div class="col-md-4">
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
        </div>
        <div class="col-md-2 text-right">
            <?php
            if ($filter->teamId > 0)
                echo Html::a(\Yii::t('team', 'Go to report...'), Url::to(['report/view', 'id' => $filter->teamId]), [
                    'class' => 'btn btn-default'
                ])
                ?>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="clearfix"></div>