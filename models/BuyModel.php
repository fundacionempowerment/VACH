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

        $user = User::findOne(['id' => Yii::$app->user->id]);
        $this->buyerEmail = $user->email;

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
            ['amount', 'number', 'min' => 10],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'amount' => Yii::t('payment', 'Money to add'),
        ];
    }
    
    public function getSignature(){
        
        $string  =  "$this->apiKey~$this->merchantId~$this->referenceCode~$this->amount~$this->currency";
        
        return md5($string);        
    }

}
