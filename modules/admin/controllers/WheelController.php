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
use app\models\Question;
use app\models\Wheel;
use app\models\WheelAnswer;
use app\models\WheelQuestion;

class WheelController extends AdminBaseController
{

    public $layout = '//admin';

    public function actionQuestions()
    {
        $wheelQuestions = WheelQuestion::find()->orderBy('type, dimension, order')->all();

        if (Yii::$app->request->isPost) {
            foreach ($wheelQuestions as $wheelQuestion) {
                $questionText = Yii::$app->request->post('question' . $wheelQuestion->id);
                $questionId = Question::getId($questionText);
                $wheelQuestion->question_id = $questionId;
                $wheelQuestion->save();
            }

            \app\controllers\SiteController::addFlash('success', Yii::t('wheel', 'Wheel questions saved.'));
            return $this->redirect(['/site']);
        }

        return $this->render('questions', [
                    'questions' => $wheelQuestions
        ]);
    }

}
