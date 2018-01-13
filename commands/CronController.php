<?php

namespace app\commands;

use yii\console\Controller;
use app\controllers\LogController;

class CronController extends Controller
{

    /**
     * This command creates a db backup and send it to administrator.
     */
    public function actionBackup()
    {
        LogController::log('Backup update called');
        \app\components\Backup::createAndSend();
    }

    /**
     * This command update currency table with BCRA rates.
     */
    public function actionCurrencyFetch()
    {
        LogController::log('Currency update called');
        \app\models\Currency::fetchLastValue();
    }

}
