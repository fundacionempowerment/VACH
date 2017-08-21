<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$dimensions = WheelQuestion::getDimensionNames($wheel->type);
$questions = WheelQuestion::getQuestions($wheel);
$setQuantity = count($questions) / 8;

for ($i = $current_dimension * $setQuantity; $i < ($current_dimension + 1) * $setQuantity; $i++)
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
    <div class="row col-md-12">
        <h3><?= $dimensions[$current_dimension] ?></h3>
        <h5>
            <b><?= Yii::t('app', 'Description') ?>:</b> <?= app\components\Dimensions::descriptions[$wheel->type][$current_dimension] ?>
        </h5>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
    <?= Html::hiddenInput('id', $wheel->id) ?>
    <?= Html::hiddenInput('current_dimension', $current_dimension) ?>
    <?php
    for ($i = $current_dimension * $setQuantity; $i < ($current_dimension + 1) * $setQuantity; $i++) {
        ?>
        <div class="row col-md-12" style="margin-top: 10px;">
            <label class="control-label" for="loginmodel-email"><?= $questions[$i]->question->wheelText($wheel) ?></label>
        </div>
        <div class="row col-md-12 <?= $showMissingAnswers && !isset($answers[$i]) ? 'alert-danger' : '' ?>">
            <div class="btn-group btn-group-lg" data-toggle="buttons">
                <?php for ($n = 0; $n <= 4; $n++) { ?>
                    <label class="btn btn-default <?= isset($answers[$i]) && $answers[$i] == $n ? 'active' : '' ?>">
                        <input type="radio" name="<?= 'answer' . $i ?>" value="<?= $n ?>" <?= isset($answers[$i]) && $answers[$i] == $n ? 'checked' : '' ?>><?= $n ?>
                    </label>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <div>
        <br/><br/>
        <?php
        if ($current_dimension < 7) {
            echo Html::submitButton(
                    $wheel->type == Wheel::TYPE_INDIVIDUAL ?
                            Yii::t('wheel', 'Save and next dimension...') :
                            Yii::t('wheel', 'Save and next competence...')
                    , ['class' => 'btn btn-lg btn-primary']);
        } else
            echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-lg btn-success']);
        echo "<br/><br/>";
        if (isset(Yii::$app->user))
            if (isset(Yii::$app->user->identity)) {
                echo Html::a(Yii::t('wheel', 'Back to team board'), ['team/view', 'id' => $wheel->team->id], ['class' => 'btn btn-default']);
            }
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'dummy_modal',
    'size' => Modal::SIZE_SMALL,
]);
?>
<?php Modal::end(); ?>