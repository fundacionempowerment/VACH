<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\httpclient\Client;
use app\models\Log;
use app\controllers\LogController;
use app\models\Currency;

class Currency extends ActiveRecord
{

    public function __construct()
    {
        $this->stamp = date('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['from_currency', 'to_currency', 'rate', 'stamp'], 'required'],
        ];
    }

    public function behaviors()
    {
        return [
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'from_currenct' => Yii::t('currency', 'From Currency'),
            'to_currency' => Yii::t('currency', 'To Currency'),
            'rate' => Yii::t('currency', 'Rate'),
        ];
    }

    public static function browse()
    {
        return Currency::find()->orderBy('id desc');
    }

    public static function lastValue()
    {
        $lastRate = Currency::find()->orderBy('stamp desc')->one();

        if (!$lastRate) {
            Currency::fetchLastValue();
            $lastRate = Currency::find()->orderBy('stamp desc')->one();
        }
        if ($lastRate->stamp < (new \DateTime('today -1 days'))->format('Y-m-d H:i:s')) {
            Currency::fetchLastValue();
            $lastRate = Currency::find()->orderBy('stamp desc')->one();
        }

        if (!$lastRate) {
            throw new Exception('Currency rate could not be fetched. Contact VACH administrator!');
        }

        return $lastRate->rate;
    }

    public static function fetchLastValue()
    {
        $client = new Client();

        $response = $client->createRequest()
                ->setMethod('get')
                ->setUrl('http://www.bcra.gov.ar/')
                ->setOptions([
                    'timeout' => 5, // set timeout to 5 seconds for the case server is not responding
                ])
                ->send();

        if (!$response->isOk) {
            LogController::log('Error: fail to get data from BCRA');

            Yii::$app->mailer->compose('currency_error')
                    ->setSubject('Currency fetch error')
                    ->setFrom(Yii::$app->params['senderEmail'])
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->send();

            return;
        }

        LogController::log('Data obteined from BCRA');

        $content = $response->content;

        $init = strpos($content, 'contenedordolar');
        $end = strpos($content, 'lar Mayorista');

        $data = substr($content, $init, $end - $init);

        $init = strpos($data, '<h3>$ ') + 6;
        $end = strpos($data, '</h3>');

        $number = substr($data, $init, $end - $init);
        $number = str_replace(',', '.', $number);

        $value = floatval($number);

        LogController::log('USD/ARS rate: ' . $number);

        $newCurrency = new Currency();
        $newCurrency->from_currency = 'ARS';
        $newCurrency->to_currency = 'USD';
        $newCurrency->rate = $value;

        if (!$newCurrency->save()) {
            $errors = $newCurrency->getErrors();
            LogController::log('Currency not saved: ' . print_r($errors));
        }

        LogController::log('Currency saved');
    }

}
