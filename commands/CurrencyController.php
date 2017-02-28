<?php

namespace app\commands;

use yii\console\Controller;
use yii\httpclient\Client;
use app\models\Log;
use app\controllers\LogController;
use app\models\Currency;

class CurrencyController extends Controller
{

    /**
     * This command update currency table with BCRA rates.
     */
    public function actionUpdate()
    {
        LogController::log('Currency update called');

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('get')
                ->setUrl('http://www.bcra.gov.ar/')
                ->send();

        if (!$response->isOk) {
            LogController::log('Error: fail to get data from BCRA');
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
