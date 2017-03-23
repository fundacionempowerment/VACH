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

if (!empty($assessment) && $assessment->version == 2) {
    $version = 2;
} else {
    $version = 1;
}

$this->title = Yii::t('report', 'Performance Matrix');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = ['label' => $assessment->fullname, 'url' => ['/assessment/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('report', 'Report'), 'url' => ['/report/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-technical">

    <h1>
        <?= Yii::t('report', 'Performance Matrix of {member}', ['member' => $report->member->fullname]) ?>
    </h1>
    <?php
    if (count($groupPerformanceMatrix) > 0) {
        echo $this->render('../dashboard/_matrix', [
            'assessmentId' => $assessment->id,
            'memberId' => $report->member->id,
            'wheelType' => Wheel::TYPE_GROUP,
        ]);
    }
    ?>
    <?php
    if (count($organizationalPerformanceMatrix) > 0) {
        echo $this->render('../dashboard/_matrix', [
            'assessmentId' => $assessment->id,
            'memberId' => $report->member->id,
            'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
        ]);
    }
    ?>
    <div class="row col-md-12">
        <h3>
            Descripción <a class="collapsed btn btn-default" aria-controls="collapsedDiv" aria-expanded="false" href="#collapsedDiv" data-toggle="collapse" role="button">
                <?= Yii::t('dashboard', 'Show') ?>
            </a>
        </h3>
    </div>
    <div id="collapsedDiv" class="panel-collapse collapse row col-md-12" aria-expanded="false">
        <?= $this->render('descriptions/individual_performance') ?>
    </div>
    <div class="row col-md-12">
        <h3>
            Análisis
        </h3>
        <p>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'newassessment-form',
            ]);
            ?>
            <?=
            SCEditor::widget([
                'name' => 'analysis',
                'value' => $report->performance,
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
