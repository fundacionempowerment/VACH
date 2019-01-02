<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "article".
 *
 * @property double $amount
 *
 */
class BuyModel extends Model
{
    public $product_id;
    public $quantity;
    public $price;

    public $merchantId;
    public $accountId;
    public $apiKey;
    public $description;
    public $referenceCode;
    public $amount;
    public $tax;
    public $taxReturnBase;
    public $currency;
    public $test;
    public $buyerEmail;
    public $payerEmail;

    public $actionUrl;
    public $responseUrl;
    public $confirmationUrl;

    public function init()
    {
        $this->merchantId = Yii::$app->params['payu_merchant_id'];
        $this->accountId = Yii::$app->params['payu_account_id'];
        $this->apiKey = Yii::$app->params['payu_api_key'];

        $this->description = Yii::t('payment', 'VACH');

        $this->tax = Yii::$app->params['payu_tax'];
        $this->taxReturnBase = Yii::$app->params['payu_tax_return_base'];
        $this->currency = Yii::$app->params['payu_currency'];
        $this->test = Yii::$app->params['payu_test'];

        $this->actionUrl = Yii::$app->params['payu_action_url'];
        $this->responseUrl = Yii::$app->params['payu_response_url'];
        $this->confirmationUrl = Yii::$app->params['payu_confirmation_url'];

        return parent::init();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['amount', 'uuid'], 'required'],
            [['product_id', 'quantity'], 'safe'],
            ['quantity', 'number', 'min' => 1, 'max' => 100],
            ['payerEmail', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'quantity' => Yii::t('stock', 'Quantity to buy'),
            'payerEmail' => Yii::t('stock', 'Sent payment link to this email'),
        ];
    }

    public function getSignature()
    {
        $string = "$this->apiKey~$this->merchantId~$this->referenceCode~$this->amount~$this->currency";

        return md5($string);
    }

    public static function fromPayment(Payment $payment)
    {
        $model = new BuyModel([
            'product_id' => $payment->stocks[0]->product_id,
            'quantity' => count($payment->stocks),
            'price' => $payment->stocks[0]->price,
            'referenceCode' => $payment->uuid,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'buyerEmail' => $payment->coach->email,
        ]);

        return $model;
    }

    public static function fromTransaction(Transaction $transaction)
    {
        $payment = $transaction->payment;

        $model = new BuyModel([
            'product_id' => $payment->stocks[0]->product_id,
            'quantity' => count($payment->stocks),
            'price' => $payment->stocks[0]->price,
            'referenceCode' => $transaction->uuid,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'buyerEmail' => $payment->coach->email,
        ]);

        return $model;
    }

}
