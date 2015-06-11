<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $coachee->id == 0 ? Yii::t('user', 'New coachee') : $coachee->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'My Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=

$this->render('_form', [
    'coachee' => $coachee,
])
?>
