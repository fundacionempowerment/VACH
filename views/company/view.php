<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $company->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'My Companies'), 'url' => ['/company']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row col-md-12">
        <h3><?= Yii::t('company', 'Company data') ?></h3>
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($company->coach->fullname) ?><br />
            <?= Yii::t('app', 'Email') ?>: <?= Html::label($company->email) ?>
        </p>
        <?= Html::a(Yii::t('company', 'Edit company'), Url::to(['company/edit', 'id' => $company->id]), ['class' => 'btn btn-default']) ?>
    </div>
</div>
