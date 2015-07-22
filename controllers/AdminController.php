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
use app\models\AccountModel;
use app\models\Feedback;

class AdminController extends Controller {

    public $layout = 'inner';

    public function actionFeedback() {
        $feedbacks = Feedback::find()->orderby('id desc');
        return $this->render('feedback', [
                    'feedbacks' => $feedbacks,
        ]);
    }

}

