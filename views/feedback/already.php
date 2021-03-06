<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('app', 'Thanks!');
?>
<div class="site-wheel">
    <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
    <h2>
        <?= Yii::t('feedback', 'You have already given us a feedback. We are waiting to hear a new feedback next month. Thank you!') ?>
    </h2>
    <p>
        <?= Html::a(Yii::t('app', 'Home'), ['/site'], ['class' => 'btn btn-default']) ?>
    </p>
</div>
