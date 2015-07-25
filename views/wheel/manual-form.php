<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonGroup;
use app\models\WheelAnswer;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$dimensions = WheelQuestion::getDimensionNames($wheel->type);
$questions = WheelQuestion::getQuestions($wheel->type);
$setQuantity = count($questions) / 8;

for ($i = 0; $i < count($questions); $i++)
    $answers[$i] = null;

foreach ($wheel->answers as $answer)
    $answers[$answer->answer_order] = $answer->answer_value;

if ($wheel->type == Wheel::TYPE_INDIVIDUAL) {
    $this->title = Yii::t('wheel', 'Running individual wheel');
} else if ($wheel->type == Wheel::TYPE_GROUP) {
    $this->title = Yii::t('wheel', 'Running group wheel');
} else {
    $this->title = Yii::t('wheel', 'Running organizational wheel');
}
$this->title = $wheel->assessment->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $wheel->assessment->team->fullname, 'url' => ['/team/view', 'id' => $wheel->assessment->team->id]];
$this->params['breadcrumbs'][] = ['label' => $wheel->assessment->name, 'url' => ['/assessment/view', 'id' => $wheel->assessment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wheel-manual">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br />
    <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br />
    <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
    <?php
    for ($i = 0; $i < count($questions); $i++) {
        ?>
        <?php
        if ($i % $setQuantity == 0) {
            if (($i / $setQuantity) % 2 == 0)
                echo '<div class="clearfix"></div>';
            ?>
            <div class="row col-md-6">
                <h3>
                    <?= $dimensions[$i / $setQuantity] ?>
                    <div class="btn-group" role="group" aria-label="...">
                        <?php for ($n = 0; $n <= 4; $n++) { ?> 
                            <button type="button" class="btn btn-default" onclick="setValues(<?= $i / $setQuantity ?>, <?= $n ?>);"><?= $n ?></button>
                        <?php } ?>
                    </div>
                </h3>
                <table class="table table-bordered">
                <?php } ?>
                <tr>
                    <td style="text-align: right;"><?= $questions[$i]['question'] ?></td>
                    <td><?= Html::textInput('answer' . $i, $answers[$i], ['size' => '2', 'style' => in_array($i, $invalids) ? 'box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 6px #CE8483' : '']) ?></td>
                </tr>
                <?php if ($i % $setQuantity == $setQuantity - 1) { ?>
                </table>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="clearfix"></div>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
                                function setValues(dimension, value)
                                {
                                    var inputs = document.getElementsByTagName('input');
                                    for (i = dimension * <?= $setQuantity ?>; i < (dimension + 1) * <?= $setQuantity ?>; i++) {
                                        for (var j in inputs) {
                                            if (inputs[j].name === 'answer' + i) {
                                                inputs[j].value = value;
                                            }
                                        }
                                    }
                                }
</script>

