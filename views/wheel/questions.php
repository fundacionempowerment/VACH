<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

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
        for ($i = 0; $i < 80; $i++) {
            $dimension = $i / 10;
            ?>
            <?= $i % 10 == 0 ? '<div class="box"><h3>' . $dimensions[$dimension] . '</h3>' : '' ?>
            <p>
                <?= Html::input('text', 'question' . $i, $questions[$i]['question'], ['class' => 'col-md-12']) ?>
            </p>
            <p>
                <?=
                Html::dropDownList('answer' . $i, $questions[$i]['answer_type'], WheelAnswer::getAnswerTypes(), ['class' => 'col-md-12'])
                ?>
            </p>
            <?= $i % 10 == 9 ? '</div>' : '' ?>
        <?php } ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
