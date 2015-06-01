<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = $wheel->id == 0 ? Yii::t('wheel', 'New wheel') : Yii::t('user', 'Answers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = ['label' => $wheel->coachee->fullname, 'url' => ['/coachee/view', 'id' => $wheel->coachee->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('wheel', 'Wheel'), 'url' => ['/wheel']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br />
    <?= Yii::t('user', 'Coachee') ?>: <?= Html::label($wheel->coachee->fullname) ?><br />
    <?= Yii::t('wheel', 'Date') ?>: <?= Html::label($wheel->date) ?><br />
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
        <?php
        for ($i = 0; $i < 80; $i++) {
            $dimension = $i / 10;
            ?>
            <?= $i % 10 == 0 ? '<div class="col-md-12"><h3>' . $dimensions[$dimension] . '</h3>' : '' ?>
            <label class="control-label" for="loginmodel-email"><?= $questions[$i]['question'] ?></label>

            <?=
            Html::radioList(
                    'answer' . $i, isset($answers[$i]) ? $answers[$i]->answer_value : null, WheelAnswer::getAnswerLabels($questions[$i]['answer_type']), ['itemOptions' => ['labelOptions' => ['style' => 'font-weight: unset;',
                        'class' => isset($answers[$i]) && isset($answers[$i]->answer_value) ? '' : 'alert-danger']]]
            )
            ?><br/>
            <?= $i % 10 == 9 ? '</div>' : '' ?>
        <?php } ?>

        <?php if ($wheel->id == 0) { ?>

            <div class="form-group">
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <?= Html::submitButton('Enviar datos', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
                </div>
            </div>
        <?php } ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
