<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use yii\bootstrap\Button;
use app\models\Wheel;
use franciscomaya\sceditor\SCEditor;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */



$this->title = Yii::t('report', 'Competences Matrix');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = ['label' => $assessment->fullname, 'url' => ['/assessment/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('report', 'Technical Report'), 'url' => ['/report/technical', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-technical">

    <h1>
        <?= Yii::t('report', 'Competences Matrix') ?>
    </h1>
    <?php
    if (count($groupGauges) > 0) {
        echo $this->render('../dashboard/_gauges', [
            'gauges' => $groupGauges,
            'type' => Wheel::TYPE_GROUP,
        ]);
    }
    ?>
    <?php
    if (count($organizationalGauges) > 0) {
        echo $this->render('../dashboard/_gauges', [
            'gauges' => $organizationalGauges,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
        ]);
    }
    ?>
    <div class="row col-md-12">
        <p>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'newassessment-form',
            ]);
            ?>
            <?=
            SCEditor::widget([
                'name' => 'analysis',
                'value' => $assessment->report->competences,
                'options' => ['rows' => 10],
                'clientOptions' => [
                    'toolbar' => "bold,italic,underline|bulletlist,orderedlist|removeformat",
                    'width' => '100%',
                ]
            ])
            ?>
        </p>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
