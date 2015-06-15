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
        if (Yii::$app->request->get('coachee_id')) {
            Yii::$app->session->set('coachee_id', Yii::$app->request->get('coachee_id'));
            Yii::$app->session->set('wheelid', null);
            Yii::$app->session->set('compareid', -1);
        }

        if (Yii::$app->request->get('wheelid')) {
            Yii::$app->session->set('wheelid', Yii::$app->request->get('wheelid'));
        }

        if (Yii::$app->request->get('compareid')) {
            Yii::$app->session->set('compareid', Yii::$app->request->get('compareid'));
        }

        $coachee_id = Yii::$app->session->get('coachee_id');
        $wheelid = Yii::$app->session->get('wheelid');
        $compareId = Yii::$app->session->get('compareid');

        if ($wheelid > 0) {
            $model = Wheel::find()->where(['id' => $wheelid])->one();
        } else {
            $model = Wheel::find()->where(['coachee_id' => $coachee_id])->orderBy('id desc')->one();
        }

        if (!isset($model))
            $this->redirect(['/site']);

        $compareModel = new Wheel();
        if ($compareId > 0) {
            $compareModel = Wheel::findOne(['id' => $compareId]);
        }

        $wheels = Wheel::browse($model->coachee->id);

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

    public function actionRun() {
        $showMissingAnswers = false;
        $current_dimension = 0;

        if (Yii::$app->request->isGet) {
            if (Yii::$app->request->get('token') != null) {
                $token = Yii::$app->request->get('token');
                $wheel = Wheel::findOne(['token' => $token]);
            } else {
                $this->redirect(['/site']);
            }
        } else if (Yii::$app->request->isPost) {
            $current_dimension = Yii::$app->request->post('current_dimension');
            $id = Yii::$app->request->post('id');
            $wheel = Wheel::findOne(['id' => $id]);

            $count = 0;

            for ($i = 0; $i < 80; $i++) {
                $new_answer_value = Yii::$app->request->post('answer' . $i);

                if (isset($new_answer_value)) {
                    $count += 1;
                    $answer = null;
                    foreach ($wheel->answers as $lookup_answer)
                        if ($lookup_answer->answer_order == $i) {
                            $answer = $lookup_answer;
                            break;
                        }

                    if (isset($answer)) {
                        $answer->answer_order = $i;
                        $answer->answer_value = $new_answer_value;
                    } else {
                        $new_answer = new WheelAnswer();
                        $new_answer->answer_order = $i;
                        $new_answer->answer_value = $new_answer_value;
                        $wheel->link('answers', $new_answer, ['wheel_id', 'id']);
                    }
                }
            }

            if ($count == 10)
                $current_dimension += 1;
            else {
                \Yii::$app->session->addFlash('error', \Yii::t('wheel', 'Some answers missed'));
                $showMissingAnswers = true;
            }

            if ($wheel->validate()) {
                $wheel->save();
                if (count($wheel->answers) == 80)
                    return $this->redirect(['/wheel', 'wheelid' => $wheel->id]);
            }
        }

        $questions = WheelQuestion::find()->asArray()->all();

        return $this->render('form', [
                    'wheel' => $wheel,
                    'current_dimension' => $current_dimension,
                    'dimensions' => $this->dimensions,
                    'questions' => $questions,
                    'showMissingAnswers' => $showMissingAnswers,
        ]);
    }

    public function actionDelete($id) {
        $wheel = Wheel::findOne(['id' => $id]);
        if ($wheel->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('wheel', 'Wheel deleted.'));
        } else {
            \Yii::$app->session->addFlash('error', \Yii::t('wheel', 'Wheel not delete:')
                    . $wheel->getErrors());
        }
        return $this->redirect(['/coachee/view', 'id' => $wheel->coachee->id]);
    }

    public function actionAnswers($id) {
        $wheel = Wheel::findOne(['id' => $id]);
        $questions = WheelQuestion::find()->asArray()->all();

        if (Yii::$app->request->get('printable') != null)
            $this->layout = 'printable';

        return $this->render('answers', [
                    'wheel' => $wheel,
                    'dimensions' => $this->dimensions,
                    'questions' => $questions,
        ]);
    }

    public function actionQuestions() {
        $questions = WheelQuestion::find()->asArray()->all();

        if (Yii::$app->request->isPost) {

            $update_questions = WheelQuestion::find()->all();
            foreach ($update_questions as $update_question) {
                $new_question_text = Yii::$app->request->post('question' . ($update_question->order - 1));
                $new_answer_type = Yii::$app->request->post('answer' . ($update_question->order - 1));

                $update_question->question = $new_question_text;
                $update_question->answer_type = $new_answer_type;
                $update_question->save();
            }

            \Yii::$app->session->addFlash('success', \Yii::t('wheel', 'Wheel questions saved.'));

            return $this->redirect(['/site']);
        }

        return $this->render('questions', [
                    'dimensions' => $this->dimensions,
                    'questions' => $questions,
        ]);
    }

}

