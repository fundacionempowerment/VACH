<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\CoachModel;
use app\models\ClientModel;
use app\models\Account;
use app\models\Currency;

class AccountController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $models = Account::browse();

        return $this->render('index', [
                    'models' => $models,
        ]);
    }

    public function actionAdd($amount = null)
    {
        if (!$amount) {
            $amount = Yii::$app->params['licence_cost'] * 10 * Currency::lastValue();
        }

        return $this->render('add', [
                    'amount' => $amount,
        ]);
    }

}
