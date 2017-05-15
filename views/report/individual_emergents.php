<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use yii\bootstrap\Button;
use app\models\Wheel;
use dosamigos\ckeditor\CKEditor;
use app\controllers\ReportController;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */



$this->title = Yii::t('report', 'Emergents Matrix');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = ['label' => $assessment->fullname, 'url' => ['/assessment/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('report', 'Report'), 'url' => ['/report/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-technical">
    <h1>
        <?= Yii::t('report', 'Emergents Matrix of {member}', ['member' => $report->member->fullname]) ?>
    </h1>
    <div class="col-md-6 ">
        <?php
        if (count($groupEmergents) > 0) {
            echo $this->render('../dashboard/_emergents', [
                'data' => $groupRelationsMatrix,
                'members' => $members,
                'memberId' => $memberId,
                'emergents' => $groupEmergents,
                'type' => Wheel::TYPE_GROUP,
                'member' => $report->member,
            ]);
        }
        ?>
    </div>
    <div class="col-md-6">
        <?php
        if (count($organizationalEmergents) > 0) {
            echo $this->render('../dashboard/_emergents', [
                'data' => $organizationalRelationsMatrix,
                'members' => $members,
                'memberId' => $memberId,
                'emergents' => $organizationalEmergents,
                'type' => Wheel::TYPE_ORGANIZATIONAL,
                'member' => $report->member,
            ]);
        }
        ?>
    </div>
    <div class="row col-md-12">
        <h3>
            Descripción <a class="collapsed btn btn-default" aria-controls="collapsedDiv" aria-expanded="false" href="#collapsedDiv" data-toggle="collapse" role="button">
                <?= Yii::t('dashboard', 'Show') ?>
            </a>
        </h3>
    </div>
    <div id="collapsedDiv" class="panel-collapse collapse row col-md-12" aria-expanded="false">
        <?= $this->render('descriptions/individual_emergents') ?>
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
            CKEditor::widget([
                'name' => 'analysis',
                'value' => $report->emergents,
                'preset' => 'custom',
                'clientOptions' => ReportController::CKEDITOR_OPTIONS
            ])
            ?>
        </p>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
