<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\Wheel;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

if ($wheel->type == Wheel::TYPE_INDIVIDUAL) {
    $this->title = Yii::t('wheel', 'Running individual wheel');
} else if ($wheel->type == Wheel::TYPE_GROUP) {
    $this->title = Yii::t('wheel', 'Running group wheel');
} else {
    $this->title = Yii::t('wheel', 'Running organizational wheel');
}
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br />
        <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br />
        <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br />
    </h2>
    <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
    <input type="hidden" name="id" value="<?= $wheel->id ?>"/>
    <input type="hidden" name="current_dimension" value="<?= $current_dimension ?>"/>
    <?= Html::submitButton(Yii::t('app', 'Begin'), ['class' => 'btn btn-primary']); ?>
    <br/><br/>
    <?php
    if (isset(Yii::$app->user))
        if (isset(Yii::$app->user->identity))
            if (Yii::$app->user->identity->is_coach) {
                echo Html::a(Yii::t('wheel', 'Back to assessment board'), ['assessment/view', 'id' => $wheel->assessment->id], ['class' => 'btn btn-default']);
            }
    ?>
</div>
