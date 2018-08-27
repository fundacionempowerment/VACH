<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\Currency;
use app\models\Account;

/* @var $model app\models\BuyModel */

$this->title = Yii::t('app', 'Redirecting...');

$this->registerJs(
    "$(document).ready(function () { window.document.forms[0].submit(); });",
    View::POS_READY,
    'form-submit'
);
?>
<div class="col-md-12">    
    <div class="text-center">
        <div class="jumbotron">
            <?= Yii::$app->user->isGuest ? Html::img('@web/images/logo.png') . '<br><br>' : '' ?>
            <h3><?= Yii::t('app', 'Redirecting...') ?></h3>
            <img src="images/red-loading.gif" />
        </div>
        <form method="post" action="<?= $model->actionUrl ?>">
            <input name="merchantId" type="hidden" value="<?= $model->merchantId ?>" >
            <input name="accountId" type="hidden" value="<?= $model->accountId ?>" >
            <input name="description" type="hidden" value="<?= $model->description ?>" >
            <input name="referenceCode" type="hidden" value="<?= $model->referenceCode ?>" >
            <input name="amount" type="hidden" value="<?= $model->amount ?>" >
            <input name="tax" type="hidden" value="<?= $model->tax ?>" >
            <input name="taxReturnBase" type="hidden" value="<?= $model->taxReturnBase ?>" >
            <input name="currency" type="hidden" value="<?= $model->currency ?>" >
            <input name="signature" type="hidden" value="<?= $model->signature ?>" >
            <input name="test" type="hidden" value="<?= $model->test ? '1' : '0' ?>" >
            <input name="buyerEmail" type="hidden" value="<?= $model->buyerEmail ?>" >
            <input name="responseUrl" type="hidden" value="<?= $model->responseUrl ?>" >
            <input name="confirmationUrl" type="hidden" value="<?= $model->confirmationUrl ?>" >
        </form>
    </div>
</div>
