<?php

use yii\helpers\Html;
?>
<div class="container">
    <p class="pull-left">
        <?= Html::a('Fundación Empowerment', 'http://www.fundacionempowerment.org/') ?>
        &nbsp;
        <?= Html::a('Español', ['site/es']) ?>
        &nbsp;
        <?= Html::a('English', ['site/en']) ?>
        &nbsp;
        <?= Html::a(Yii::t('feedback', 'Feedbacks'), ['/feedback']) ?>
        &nbsp;
        <?= Html::a(Yii::t('feedback', 'Tutorial'), '@web/tutorial.pdf') ?>
    </p>
    <p class="pull-right">
        <?= Yii::t('app', 'Powered by') ?>
        <?= Html::a('Yii Framework', 'http://www.yiiframework.com/', ['rel' => 'external', 'target' => '_blank']) ?>
    </p>
</div>