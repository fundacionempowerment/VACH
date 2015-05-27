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
use app\models\WheelModel;
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

        $userId = Yii::$app->session->get('clientid');
        $wheelid = Yii::$app->session->get('wheelid');
        $compareId = Yii::$app->session->get('compareid');

        $model = new WheelModel();
        $model->coacheeId = $userId;

        if ($wheelid == 0) {
            $model->populateLast();
            $wheelid = $model->id;
        } else {
            $model->id = $wheelid;
            $model->populate();
        }

        $compareModel = new WheelModel();
        if ($compareId > 0) {
            $compareModel->id = $compareId;
            $compareModel->populate();
        }


        $wheels = $model->browse();
        $wheelArray = [];
        foreach ($wheels as $wheel) {
            $wheelArray[$wheel['id']] = $wheel['date'];
        }

        if ($model->id == 0)
            return $this->redirect(['form', 'id' => 0]);
        else
            return $this->render('view', [
                        'model' => $model,
                        'compare' => $compareModel,
                        'wheels' => $wheelArray,
                        'id' => $wheelid,
                        'compareId' => $compareId,
                        'dimensions' => $this->shortDimensions,
            ]);
    }

    public function actionForm() {
        $showMissingAnswers = false;

        $model = new WheelModel();
        if (Yii::$app->request->isPost) {
            $showMissingAnswers = true;

            $model->coacheeId = $userId = Yii::$app->session->get('clientid');
            $model->date = date(DATE_ATOM);

            for ($i = 0; $i < 80; $i++) {
                $answer = Yii::$app->request->post('answer' . $i);
                if (isset($answer))
                    $model->answers[$i] = $answer;
            }

            if ($model->validate()) {
                $model->save();
                return $this->redirect(['index']);
            }
        } else {
            $id = Yii::$app->request->get('Id');

            if ($id > 0) {
                $model->id = $id;
                $model->populate();
            }
        }

        if (defined('YII_DEBUG')) {
            if (!is_array($model->answers)) {
                for ($i = 0; $i < 80; $i++)
                    $model->answers[] = rand(0, 4);
            } else {
                for ($i = 0; $i < 80; $i++)
                    if (!isset($answer[$i]))
                        $answer[$i] = rand(0, 4);
                    else if ($answer[$i] < 0 || $answer[$i] > 4)
                        $answer[$i] = rand(0, 4);
            }
        }

        if ($model->hasErrors()) {
            \Yii::$app->session->addFlash('error', \Yii::t('wheel', 'Some answer missed'));
        }

        $questions = WheelQuestion::find()->asArray()->all();

        return $this->render('details', [
                    'model' => $model,
                    'questions' => $questions,
                    'dimensions' => $this->dimensions,
                    'showMissingAnswers' => $showMissingAnswers,
        ]);
    }

}
