<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('wheel', 'Editing wheel questions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-12">
        <?php $form = ActiveForm::begin(['id' => 'questions-form']); ?>
        <?php
        $current_dimension = -1;
        $div_open = false;
        foreach ($questions as $question) {
            $dimensions = WheelQuestion::getDimensionNames($question->type);

            if ($current_dimension != $question->dimension) {
                $current_dimension = $question->dimension;

                if ($div_open == true) {
                    echo '</div>';
                    $div_open = false;
                }

                echo '<div class="box"><h3>' . $dimensions[$question->dimension] . '</h3>';
                $div_open = true;
            }
            ?>
            <p>
                <?= Html::input('text', 'question' . $question->id, $question->question, ['class' => 'col-md-12']) ?>
            </p>
            <p>
                <?=
                Html::dropDownList('answer' . $question->id, $question->answer_type, WheelAnswer::getAnswerTypes(), ['class' => 'col-md-12'])
                ?>
            </p>
        <?php } ?>
        <?= '</div>' ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
