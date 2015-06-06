<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

foreach ($wheel->answers as $answer)
    $answers[$answer->answer_order] = $answer->answer_value;

$this->title = $wheel->id == 0 ? Yii::t('wheel', 'New wheel') : Yii::t('user', 'Answers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'My Coachees'), 'url' => ['/coachee']];
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
                    'answer' . $i, $answers[$i], WheelAnswer::getAnswerLabels($questions[$i]['answer_type']), ['itemOptions' => ['labelOptions' => ['style' => 'font-weight: unset;']]]
            )
            ?><br/>
            <?= $i % 10 == 9 ? '</div>' : '' ?>
        <?php } ?>
        <?php ActiveForm::end(); ?>
        <?= Yii::$app->request->get('printable') == null ? Html::a(Yii::t('app', 'Printable'), Url::to(['wheel/answers', 'id' => $wheel->id, 'printable' => 1]), ['class' => 'btn btn-default']) : '' ?>
    </div>
</div>
