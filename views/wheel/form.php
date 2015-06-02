<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

for ($i = $current_dimension * 10; $i < $current_dimension * 10 + 10; $i++)
    $answers[$i] = null;

foreach ($wheel->answers as $answer)
    if ($answer->answer_order >= $current_dimension * 10 && $answer->answer_order < $current_dimension * 10 + 10)
        $answers[$answer->answer_order] = $answer->answer_value;

$this->title = Yii::t('wheel', 'Running wheel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = ['label' => $wheel->coachee->fullname, 'url' => ['/coachee/view', 'id' => $wheel->coachee->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('wheel', 'Wheel'), 'url' => ['/wheel', 'wheelid' => $wheel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br />
    <?= Yii::t('user', 'Coachee') ?>: <?= Html::label($wheel->coachee->fullname) ?><br />
    <?= Yii::t('wheel', 'Date') ?>: <?= Html::label($wheel->date) ?><br />
    <div class="row col-md-12">
        <h3><?= $dimensions[$current_dimension] ?></h3>
        <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
        <input type="hidden" name="id" value="<?= $wheel->id ?>"/>
        <input type="hidden" name="current_dimension" value="<?= $current_dimension ?>"/>
        <?php
        for ($i = $current_dimension * 10; $i < $current_dimension * 10 + 10; $i++) {
            ?>
            <label class="control-label" for="loginmodel-email"><?= $questions[$i]['question'] ?></label>
            <?=
            Html::radioList(
                    'answer' . $i, $answers[$i], WheelAnswer::getAnswerLabels($questions[$i]['answer_type']), ['itemOptions' => ['labelOptions' => ['style' => 'font-weight: unset;',
                        'class' => $showMissingAnswers && !isset($answers[$i]) ? 'alert-danger' : '']]]
            )
            ?><br/>
        <?php } ?>


        <?php
        if ($current_dimension < 7)
            echo Html::submitButton(Yii::t('wheel', 'Save and next dimension...'), ['class' => 'btn btn-primary']);
        else
            echo Html::submitButton(Yii::t('wheel', 'Save and finish'), ['class' => 'btn btn-success']);
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
