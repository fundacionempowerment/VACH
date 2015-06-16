<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('wheel', 'Answering');
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br />
        <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br />
        <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br />
    </h2>
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
        <input type="hidden" name="id" value="<?= $wheel->id ?>"/>
        <input type="hidden" name="current_dimension" value="<?= $current_dimension ?>"/>
        <?= Html::submitButton(Yii::t('app', 'Begin'), ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>
