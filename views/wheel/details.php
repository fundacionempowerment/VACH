<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\WheelModel;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = $model->id == 0 ? Yii::t('wheel', 'New wheel') : Yii::t('user', 'Answers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Wheels'), 'url' => ['/wheel']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
  <h1><?= Html::encode($this->title) ?></h1>
  <?php if ($model->hasErrors()) { ?>
    <div class="alert alert-danger">
      Faltan respuestas
    </div>
  <?php } ?>
  <div class="row">

    <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>

    <?php
    for ($i = 0; $i < 80; $i++) {
      $dimension = $i / 10;
      ?>
      <?= $i % 10 == 0 ? '<div class="col-sm-6 col-lg-4"><h3>' . $dimensions[$dimension] . '</h3>' : '' ?>
      <label class="control-label" for="loginmodel-email"><?= $questions[$i][0] ?></label>

      <?=
      Html::radioList(
              'answer' . $i, isset($model->answers[$i]) ? $model->answers[$i] : null, WheelModel::getAnswers($questions[$i][1]), ['itemOptions' => ['labelOptions' => ['style' => 'font-weight: unset;',
                  'class' => isset($model->answers[$i]) ? '' : 'alert-danger']]]
      )
      ?><br/>
      <?= $i % 10 == 9 ? '</div>' : '' ?>
    <?php } ?>

    <?php if ($model->id == 0) { ?>

      <div class="form-group">
        <div class="clearfix"></div>
        <div class="col-lg-offset-1 col-lg-11">
          <?= Html::submitButton('Enviar datos', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        </div>
      </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
  </div>
</div>
</div>
