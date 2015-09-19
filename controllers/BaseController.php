<?php

namespace app\controllers;
use Yii;

class BaseController extends \yii\web\Controller {

    public function init() {
        parent::init();

        $currentLanguage = Yii::$app->session->get('language');
        if ($currentLanguage != '') {
            Yii::$app->language = $currentLanguage;
        }
    }

}