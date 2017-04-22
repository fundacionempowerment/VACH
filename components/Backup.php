<?php

namespace app\components;

use Yii;
use app\controllers\LogController;

class Backup
{

    public static function createAndSend()
    {
        $dsn = Yii::$app->get('db')->dsn;

        preg_match('/' . 'dbname' . '=([^;]*)/', $dsn, $match);
        $db = $match[1];

        preg_match('/' . 'host' . '=([^;]*)/', $dsn, $match);
        $host = '-h ' . $match[1];

        $port = '';
        if (preg_match('/' . 'port' . '=([^;]*)/', $dsn, $match)) {
            $port = '--port=' . $match[1];
        }

        $username = '-u ' . Yii::$app->get('db')->username;
        $password = "-p'" . Yii::$app->get('db')->password . "'";

        $temp_file = tempnam(sys_get_temp_dir(), 'vach');

        LogController::log('Dumping in ' . $temp_file);
        exec("mysqldump $host $username $password $db | gzip > $temp_file");

        LogController::log('Sending to ' . Yii::$app->params['adminEmail']);
        Yii::$app->mailer->compose('backup')
                ->setSubject('VACH Backup ' . date('Y-m-d'))
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->attach($temp_file, ['fileName' => 'vach.' . date('Y-m-d') . '.sql.gz'])
                ->send();

        LogController::log('Backup done');

        return true;
    }

}
