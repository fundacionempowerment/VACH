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

class FeedbackController extends Controller {

    public function actionIndex() {
        $previous_feedback = Feedback::find()->where("ip = '" . Yii::$app->request->userIP . "' and datetime >= '" . date('Y-m-d', strtotime("-30 days")) . '\'')->all();
        if (count($previous_feedback) > 0)
            return $this->render('already');

        $feedback = new Feedback();

        if (Yii::$app->request->isPost) {
            $feedback->effectiveness = Yii::$app->request->post('effectiveness');
            $feedback->efficience = Yii::$app->request->post('efficience');
            $feedback->satisfaction = Yii::$app->request->post('satisfaction');
            $feedback->comment = Yii::$app->request->post('comment');

            if ($feedback->save()) {
                return $this->render('thanks');
            }
            else
                SiteController::FlashErrors($feedback);
        }

        return $this->render('index', [
                    'feedback' => $feedback,
        ]);
    }

}

