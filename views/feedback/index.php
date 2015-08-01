<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$time_values = [
    Yii::t('wheel', 'never'),
    Yii::t('wheel', 'sometimes'),
    Yii::t('wheel', 'often'),
    Yii::t('wheel', 'usually'),
    Yii::t('wheel', 'always')
];
$hardness_values = [
    Yii::t('feedback', 'very hard'),
    Yii::t('feedback', 'hard'),
    Yii::t('feedback', 'regular'),
    Yii::t('feedback', 'easy'),
    Yii::t('feedback', 'very easy')
];

$this->title = Yii::t('feedback', 'Feedback');
?>
<div class="site-register">
    <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <h4>
        <p>
            <?= Yii::t('feedback', 'We have worked hard to give our customers the best VACH we can develop, but as every human creation is perfectible.') ?>  
        </p>
        <p>
            <?= Yii::t('feedback', 'From that point of view, we encourage you to give us feedback about your experience:') ?> 
        </p>
    </h4>
    <br />
    <div>
        <?php $form = ActiveForm::begin([ 'id' => 'feedback-form',]); ?>
        <p>
            <label>
                <?= Yii::t('feedback', 'How many time you completed the tasks VACH is intended for?') ?>
            </label>
            <br />
            <?= Html::radioList('effectiveness', -1, $time_values, ['itemOptions' => ['labelOptions' => ['style' => 'font-weight: unset;',]]]); ?>
        </p>
        <p>
            <label>
                <?= Yii::t('feedback', 'How easy was VACH to be used?') ?>
            </label>
            <br />
            <?= Html::radioList('efficience', -1, $hardness_values, ['itemOptions' => ['labelOptions' => ['style' => 'font-weight: unset;',]]]); ?>
        </p>
        <p>
            <label>
                <?= Yii::t('feedback', "How many times you've been satisfied when use it?") ?>
            </label>
            <br />
            <?= Html::radioList('satisfaction', -1, $time_values, ['itemOptions' => ['labelOptions' => ['style' => 'font-weight: unset;',]]]); ?>
        </p>
        <p>
            <label>
                <?= Yii::t('feedback', 'Please feel free to give us a comment:') ?>
            </label>
            <br />
            <?= Html::textarea('comment', '', ['class' => 'col-lg-12', 'maxlength' => '270']); ?>
        </p>
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        <br/>
        <span style="font-size: 0.8em;">
            <?= Yii::t('feedback', 'As a security issue, feedback are limited to one a month an IP.') ?>
        </span>
        <?php ActiveForm::end(); ?>
    </div>
</div>
