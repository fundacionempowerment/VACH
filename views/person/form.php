<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $person->id == 0 ? Yii::t('user', 'New person') : $person->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'My Persons'), 'url' => ['/person']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=

$this->render('_form', [
    'person' => $person,
])
?>
