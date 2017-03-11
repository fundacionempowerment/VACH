<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Log;

class LogController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $logs = Log::browse();
        return $this->render('index', [
                    'logs' => $logs,
        ]);
    }

    public static function log($text, $coach_id = null)
    {
        $log = new Log();

        if ($coach_id) {
            $log->coach_id = $coach_id;
        } else if (isset(Yii::$app->user)) {
            $log->coach_id = Yii::$app->user->id;
        }

        $log->text = $text;
        return $log->save();
    }

}
