<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('wheel', 'Thanks!');
?>
<div class="site-wheel">
    <h2>
        <?= Yii::t('wheel', 'You\'ve successfully answered all questions!') ?>
    </h2>
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
</div>
