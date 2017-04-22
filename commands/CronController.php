<?php

namespace app\commands;

use yii\console\Controller;
use yii\httpclient\Client;
use app\models\Log;
use app\controllers\LogController;
use app\models\Currency;

class CronController extends Controller
{

    /**
     * This command update currency table with BCRA rates.
     */
    public function actionBackup()
    {
        LogController::log('Backup update called');
        \app\components\Backup::createAndSend();
    }

}
