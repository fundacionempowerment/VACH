<?php

use yii\helpers\Url;
use yii\bootstrap\Nav;
use app\components\Icons;

$isAdministrator = false;
if (Yii::$app->user->identity) {
    $isAdministrator = Yii::$app->user->identity->is_administrator;
}

$items[] = ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']];

$coachMenu[] = ['label' => Yii::t('company', 'Companies'), 'url' => ['/company']];
$coachMenu[] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/person']];
$coachMenu[] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$coachMenu[] = ['label' => Yii::t('dashboard', 'Dashboard'), 'url' => ['/dashboard']];
$items[] = ['label' => Yii::t('app', 'Clients'), 'items' => $coachMenu];

$assisstanceMenu[] = ['label' => Yii::t('help', 'Tutorial'), 'url' => Url::to('@web/docs/tutorial.es.pdf')];
$assisstanceMenu[] = ['label' => Yii::t('help', 'Empty individual wheel form'), 'url' => Url::to('@web/docs/individual.wheel.form.es.pdf')];
$assisstanceMenu[] = ['label' => Yii::t('help', 'Empty group wheel form'), 'url' => Url::to('@web/docs/group.wheel.form.es.pdf')];
$assisstanceMenu[] = ['label' => Yii::t('help', 'Empty organizational wheel form'), 'url' => Url::to('@web/docs/oganizational.wheel.form.es.pdf')];
$assisstanceMenu[] = ['label' => Yii::t('log', 'Event Log'), 'url' => ['/log']];
$items[] = ['label' => Yii::t('help', 'Help'), 'items' => $assisstanceMenu];

if ($isAdministrator) {
    $admininistratorMenu[] = ['label' => Yii::t('stock', 'Licences'), 'url' => ['/admin/stock']];
    $admininistratorMenu[] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['/admin/payment']];
    $admininistratorMenu[] = ['label' => Yii::t('payment', 'Liquidations'), 'url' => ['/admin/liquidation']];
    $admininistratorMenu[] = ['label' => Yii::t('user', 'Users'), 'url' => ['/admin/user']];
    $admininistratorMenu[] = ['label' => Yii::t('wheel', 'Wheel Questions'), 'url' => ['/admin/wheel/questions']];
    $admininistratorMenu[] = ['label' => Yii::t('feedback', 'Feedbacks'), 'url' => ['/admin/feedback']];
    $admininistratorMenu[] = ['label' => Yii::t('app', 'Backup'), 'url' => ['/site/backup']];
    $items[] = ['label' => Yii::t('app', 'Admin'), 'items' => $admininistratorMenu];
}

$userMenu[] = ['label' => Yii::t('user', 'My Data'), 'url' => ['/user/my-account']];
if (Yii::$app->params['monetize']) {
    $userMenu[] = ['label' => Yii::t('payment', 'My Payments'), 'url' => ['/payment']];
    $userMenu[] = ['label' => Yii::t('stock', 'My Licences'), 'url' => ['/stock']];
}
$userMenu[] = ['label' => Yii::t('app', 'Logout'),
    'url' => ['/site/logout'],
    'linkOptions' => ['data-method' => 'post']];
$items[] = ['label' => Icons::USER . ' (' . (Yii::$app->user->identity ? Yii::$app->user->identity->username : '') . ')', 'items' => $userMenu];

echo Nav::widget([
    'id' => 'navbar',
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $items,
    'encodeLabels' => false,
    'activateParents' => true
]);
