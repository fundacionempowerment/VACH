<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\Currency;
use app\models\Account;

$this->title = Yii::t('account', 'Add money');
?>
<div class="col-md-12">
    <h1><?= $this->title ?></h1>
    <p>
        Ingrese el monto que desea acreditar a su cuenta:
    </p>
    <p>        
        <div class="col-sm-4">
            <label>Monto a acreditar:</label>
            <?= Html::textInput('amount', $amount, ['class' => 'form-control']) ?>
        </div>
    </p>
    <div class="clearfix"></div>
    <p>
        <br/><br/>
        Su saldo actual: <?= Yii::$app->formatter->asDecimal(Account::getBalance()) ?> ARS<br/>
        Precio por licencia: <?= Yii::$app->formatter->asDecimal(Yii::$app->params['licence_cost'], 2) ?> USD<br/>
        Tasa de cambio: <?= Yii::$app->formatter->asDecimal(Currency::lastValue(), 3) ?> ARS / 1 USD<br/>
    </p>
</div>
