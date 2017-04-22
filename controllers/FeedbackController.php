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

class FeedbackController extends BaseController
{

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $this->layout = 'inner';
        }

        $previous_feedback = Feedback::getPrevious();
        if (count($previous_feedback) > 0)
            return $this->render('already');

        $feedback = new Feedback();

        if (Yii::$app->request->isPost) {
            $feedback->effectiveness = Yii::$app->request->post('effectiveness');
            $feedback->efficience = Yii::$app->request->post('efficience');
            $feedback->satisfaction = Yii::$app->request->post('satisfaction');
            $feedback->comment = Yii::$app->request->post('comment');

            if (!Yii::$app->user->isGuest)
                $feedback->user_id = Yii::$app->user->identity->id;

            if ($feedback->save()) {
                return $this->render('thanks');
            } else
                SiteController::FlashErrors($feedback);
        }

        return $this->render('index', [
                    'feedback' => $feedback,
        ]);
    }

}
