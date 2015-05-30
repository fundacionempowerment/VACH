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
use app\models\Wheel;
use app\models\WheelAnswer;
use app\models\WheelQuestion;

class WheelController extends Controller {

    public $layout = 'inner';
    public $dimensions = [
        'Tiempo libre',
        'Trabajo',
        'Familia',
        'Dimensión física',
        'Dimensión emocional',
        'Dimensión mental',
        'Dimensión existencial',
        'Dimensión espiritual',
    ];
    public $shortDimensions = [
        'Tiempo libre',
        'Trabajo',
        'Familia',
        'D. física',
        'D. emocional',
        'D. mental',
        'D. existencial',
        'D. espiritual',
    ];

    public function actionIndex() {
        if (Yii::$app->request->get('clientid')) {
            Yii::$app->session->set('clientid', Yii::$app->request->get('clientid'));
            Yii::$app->session->set('wheelid', null);
            Yii::$app->session->set('compareid', -1);
        }

        if (Yii::$app->request->get('wheelid')) {
            Yii::$app->session->set('wheelid', Yii::$app->request->get('wheelid'));
        }

        if (Yii::$app->request->get('compareid')) {
            Yii::$app->session->set('compareid', Yii::$app->request->get('compareid'));
        }

        $coachee_id = Yii::$app->session->get('clientid');
        $wheelid = Yii::$app->session->get('wheelid');
        $compareId = Yii::$app->session->get('compareid');

        if ($wheelid > 0) {
            $model = Wheel::find()->where(['id' => $wheelid])->one();
        } else {
            $model = Wheel::find()->where(['coachee_id' => $coachee_id])->orderBy('id desc')->one();
        }

        if (!isset($model))
            $model = new Wheel();

        $compareModel = new Wheel();
        if ($compareId > 0) {
            $compareModel = Wheel::findOne(['id' => $compareId]);
        }

        $wheels = Wheel::browse($coachee_id);

        if ($model->id == 0)
            return $this->redirect(['form', 'id' => 0]);
        else
            return $this->render('view', [
                        'model' => $model,
                        'compare' => $compareModel,
                        'wheels' => $wheels,
                        'dimensions' => $this->shortDimensions,
            ]);
    }

    public function actionForm() {
        $showMissingAnswers = false;
        $answers = [];

        $wheel = new Wheel();
        if (Yii::$app->request->isPost) {
            $showMissingAnswers = true;

            for ($i = 0; $i < 80; $i++) {
                $answer = Yii::$app->request->post('answer' . $i);
                if (isset($answer)) {
                    $answers[$i] = new WheelAnswer();
                    $answers[$i]->answer_order = $i;
                    $answers[$i]->answer_value = $answer;
                }
            }

            $wheel->date = date(DATE_ATOM);
            $wheel->coachee_id = Yii::$app->session->get('clientid');

            if ($wheel->customSave($answers)) {
                return $this->redirect(['index']);
            }
        } else if (Yii::$app->request->get('Id') != null) {
            $id = Yii::$app->request->get('Id');
            $wheel = Wheel::findOne(['id' => $id]);
            $answers = $wheel->answers;
        }

        if (defined('YII_DEBUG')) {
            for ($i = 0; $i < 80; $i++)
                if (!isset($answers[$i])) {
                    $answers[$i] = new \app\models\WheelAnswer();
                    $answers[$i]->answer_value = rand(0, 4);
                }
        }

        if ($wheel->hasErrors()) {
            \Yii::$app->session->addFlash('error', \Yii::t('wheel', 'Some answers missed'));
        }

        $questions = WheelQuestion::find()->asArray()->all();

        return $this->render('details', [
                    'wheel' => $wheel,
                    'dimensions' => $this->dimensions,
                    'questions' => $questions,
                    'answers' => $answers,
                    'showMissingAnswers' => $showMissingAnswers,
        ]);
    }

}

