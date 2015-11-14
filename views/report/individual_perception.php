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



$this->title = Yii::t('report', 'Perception Matrix');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = ['label' => $assessment->fullname, 'url' => ['/assessment/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('report', 'Report'), 'url' => ['/report/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
    var lineals = new Array();
    var linealsData = new Array();
</script>
<script src="<?= Url::to('@web/js/Chart.min.js') ?>"></script>
<div class="report-technical">

    <h1>
        <?= Yii::t('report', 'Perception Matrix of {member}', ['member' => $report->member->fullname]) ?>
    </h1>
    <?php
    if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0)
        echo $this->render('../dashboard/_lineal', [
            'title' => Yii::t('dashboard', 'Group Perception Matrix'),
            'wheel' => $reflectedGroupWheel,
            'wheelName' => Yii::t('dashboard', 'How they see me'),
            'comparedWheel' => $projectedGroupWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
            'type' => Wheel::TYPE_GROUP,
        ]);
    if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0)
        echo $this->render('../dashboard/_lineal', [
            'title' => Yii::t('dashboard', 'Organizational Perception Matrix'),
            'wheel' => $reflectedOrganizationalWheel,
            'wheelName' => Yii::t('dashboard', 'How they see me'),
            'comparedWheel' => $projectedOrganizationalWheel,
            'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
            'type' => Wheel::TYPE_ORGANIZATIONAL,
        ]);
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
                'value' => $report->perception,
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
<script>
    window.onload = function() {
        for (var i in lineals) {
            new Chart(document.getElementById("canvas" + lineals[i]).getContext("2d")).Line(linealsData[i], {responsive: true, scaleBeginAtZero: true, scaleFontSize: 15, scaleOverride: true, scaleSteps: 4, scaleStepWidth: 1, scaleStartValue: 0, bezierCurve: false});
        }
    }
</script>
