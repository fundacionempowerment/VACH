<?php

namespace app\modules\admin\controllers;

use Yii;

class AdminBaseController extends \app\controllers\BaseController
{
    public $layout = '/admin';

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->user->identity->is_administrator) {
            Yii::$app->response->redirect(['/site'])->send();
            return false;
        }

        return true;
    }

}
