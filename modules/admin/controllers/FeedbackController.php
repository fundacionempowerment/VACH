<?php

namespace app\modules\admin\controllers;

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

class FeedbackController extends \app\controllers\BaseController
{

    public $layout = '//admin';

    public function actionIndex()
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $feedbacks = Feedback::find()->orderby('id desc');
        return $this->render('index', [
                    'feedbacks' => $feedbacks,
        ]);
    }


}
