<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('app', 'Wheel Received');
?>
<div class="site-wheel">
    <h1>
        <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
    </h1>
    <h3 class="alert alert-success">
        <?= Yii::t('wheel', 'Your wheel has been marked as received.') ?>
    </h3>
    <h2>
        <?= Yii::t('app', 'Thanks!') ?>
    </h2>
</div>
