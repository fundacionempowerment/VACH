<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('app', 'Thanks!');
?>
<div class="site-wheel col-md-push-2 col-md-8">
    <h2>
        <?= Yii::t('wheel', 'You\'ve successfully answered all questions!') ?>
    </h2>
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <p>
        <?= \app\components\SpinnerAnchor::widget([
            'caption' => Yii::t('app', 'Home'),
            'url' => Url::to(['/site']),
            'options' => ['class' => 'btn btn-default'],
        ]) ?>
    </p>
</div>
