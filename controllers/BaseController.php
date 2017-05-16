<?php

namespace app\controllers;

use Yii;

class BaseController extends \yii\web\Controller
{

    public function init()
    {
        parent::init();

        $currentLanguage = Yii::$app->session->get('language');
        if ($currentLanguage != '') {
            Yii::$app->language = $currentLanguage;
        }
    }

    public function beforeAction($action)
    {
        if (!SiteController::checkUserSession()) {
            return false;
        }

        return parent::beforeAction($action);
    }

}
