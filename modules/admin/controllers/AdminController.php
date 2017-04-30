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
use app\models\Feedback;
use app\models\Stock;
use app\models\Payment;

class AdminController extends BaseController
{

    public $layout = 'inner';

    public function actionFeedback()
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $feedbacks = Feedback::find()->orderby('id desc');
        return $this->render('feedback', [
                    'feedbacks' => $feedbacks,
        ]);
    }

    public function actionPayment()
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $models = Payment::adminBrowse();

        return $this->render('payment', [
                    'models' => $models,
        ]);
    }

}
