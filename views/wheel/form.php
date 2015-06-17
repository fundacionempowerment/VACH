<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$dimensions = WheelQuestion::getDimensionNames($wheel->type);
$questions = WheelQuestion::getQuestions($wheel->type);
$setQuantity = count($questions) / 8;

for ($i = $current_dimension * $setQuantity; $i < ($current_dimension + 1) * $setQuantity; $i++)
    if (YII_DEBUG)
        $answers[$i] = rand(0, 4);
    else
        $answers[$i] = null;

foreach ($wheel->answers as $answer)
    if ($answer->answer_order >= $current_dimension * $setQuantity && $answer->answer_order < ($current_dimension + 1) * $setQuantity)
        $answers[$answer->answer_order] = $answer->answer_value;

if ($wheel->type == Wheel::TYPE_INDIVIDUAL) {
    $this->title = Yii::t('wheel', 'Running individual wheel');
} else if ($wheel->type == Wheel::TYPE_GROUP) {
    $this->title = Yii::t('wheel', 'Running group wheel');
} else {
    $this->title = Yii::t('wheel', 'Running organizational wheel');
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('wheel', 'Wheel'), 'url' => ['/wheel', 'wheelid' => $wheel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br />
    <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br />
    <?= Yii::t('wheel', 'Date') ?>: <?= Html::label($wheel->date) ?><br />
    <div class="row col-md-12">
        <h3><?= $dimensions[$current_dimension] ?></h3>
        <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
        <input type="hidden" name="id" value="<?= $wheel->id ?>"/>
        <input type="hidden" name="current_dimension" value="<?= $current_dimension ?>"/>
        <?php
        for ($i = $current_dimension * $setQuantity; $i < ($current_dimension + 1) * $setQuantity; $i++) {
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
        <br />
    </div>
</div>
