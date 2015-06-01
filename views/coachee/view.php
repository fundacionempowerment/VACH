<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('user', 'View coachee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row col-md-12">
        <h3><?= Yii::t('user', 'Personal data') ?></h3>
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($coachee->name) ?> <?= Html::label($coachee->surname) ?><br />
            <?= Yii::t('user', 'Coachee') ?>: <?= Html::label($coachee->name) ?> <?= Html::label($coachee->surname) ?><br />
            <?= Yii::t('user', 'Email') ?>: <?= Html::label($coachee->email) ?>
        </p>
        <?= Html::a(Yii::t('user', 'Edit coachee'), Url::to(['coachee/edit', 'id' => $coachee->id]), ['class' => 'btn btn-default']) ?>
    </div>
    <div class="row col-md-12">
        <h3><?= Yii::t('wheel', 'Wheels') ?></h3>
        <?php foreach ($coachee->wheels as $wheel) : ?>
            <?= Html::a($wheel->date, Url::to(['/wheel', 'wheelid' => $wheel['id']])) ?>
            <br />
        <?php endforeach; ?>
        <br />
        <?= Html::a(Yii::t('wheel', 'New wheel'), Url::to(['wheel/form']), ['class' => 'btn btn-success']) ?>

    </div>
</div>
