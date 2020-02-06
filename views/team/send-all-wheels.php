<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\Wheel;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $wheelsToSend array */
/* @var $team app\models\Team */

$this->title = $team->fullname . " - " . Yii::t('wheel', 'Send all wheels');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $team->fullname, 'url' => ['/team/view', 'id' => $team->id]];
$this->params['breadcrumbs'][] = $this->title;

if ($type == Wheel::TYPE_INDIVIDUAL) {
    $wheelType = Yii::t('wheel', 'Individual Wheels');
} elseif ($type == Wheel::TYPE_GROUP) {
    $wheelType = Yii::t('wheel', 'Group Wheels');
} else {
    $wheelType = Yii::t('wheel', 'Organizational Wheels');
}

?>
<div class="send-all-wheels-page">
    <h1><?= Html::encode($team->fullname) ?></h1>
    <h3><?= Yii::t('app', 'Send all') . " " . $wheelType ?></h3>
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(['id' => 'send-all-wheels-form']); ?>
        <div class="col-sm-push-1 col-sm-6">
            <?php
            foreach ($wheelsToSend as $wheelToSend) {
                $wheel = $wheelToSend['wheel'];
                $teamMember = $wheelToSend['teamMember']; ?>
                <p>
                    <?= CheckboxX::widget([
                        'name' => 'c' . $wheel->id,
                        'value' => $wheel->answerStatus != '100%',
                        'autoLabel' => true,
                        'labelSettings' => [
                            'label' => $teamMember->member->fullname,
                            'position' => CheckboxX::LABEL_RIGHT
                        ],
                        'pluginOptions' => [
                            'threeState' => false
                        ]
                    ]); ?>
                </p>
            <?php } ?>
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('app', 'Send'), ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
