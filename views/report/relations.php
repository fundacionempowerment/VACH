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

$this->title = Yii::t('report', 'Relations Matrix');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = ['label' => $assessment->fullname, 'url' => ['/assessment/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('report', 'Technical Report'), 'url' => ['/report/technical', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
    var relations = new Array();
    var relationsData = new Array();
</script>
<script src="<?= Url::to('@web/js/relations.js') ?>"></script>
<div class="report-technical">

    <h1>
        <?= Yii::t('report', 'Relations Matrix of {member}', ['member' => $report->member->fullname]) ?>
    </h1>
    <?php
    if (count($groupRelationsMatrix) > 0) {
        echo $this->render('../dashboard/_relation', [
            'data' => $groupRelationsMatrix,
            'members' => $members,
            'type' => Wheel::TYPE_GROUP,
            'memberId' => $report->member->id,
        ]);
    }
    if (count($organizationalRelationsMatrix) > 0) {
        echo $this->render('../dashboard/_relation', [
            'data' => $organizationalRelationsMatrix,
            'members' => $members,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
            'memberId' => $report->member->id,
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
                'value' => $report->relations,
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
        for (var i in relations) {
            doRelations(document.getElementById("canvas" + relations[i]).getContext("2d"), relationsData[i]);
        }
    }
</script>
