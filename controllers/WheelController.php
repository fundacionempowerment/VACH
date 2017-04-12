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
use app\models\Question;
use app\models\Wheel;
use app\models\WheelAnswer;
use app\models\WheelQuestion;

class WheelController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        if (Yii::$app->request->get('person_id')) {
            Yii::$app->session->set('person_id', Yii::$app->request->get('person_id'));
            Yii::$app->session->set('wheelid', null);
            Yii::$app->session->set('compareid', -1);
        }

        if (Yii::$app->request->get('wheelid')) {
            Yii::$app->session->set('wheelid', Yii::$app->request->get('wheelid'));
        }

        if (Yii::$app->request->get('compareid')) {
            Yii::$app->session->set('compareid', Yii::$app->request->get('compareid'));
        }

        $person_id = Yii::$app->session->get('person_id');
        $wheelid = Yii::$app->session->get('wheelid');
        $compareId = Yii::$app->session->get('compareid');

        if ($wheelid > 0) {
            $model = Wheel::find()->where(['id' => $wheelid])->one();
        } else {
            $model = Wheel::find()->where(['person_id' => $person_id])->orderBy('id desc')->one();
        }

        if (!isset($model))
            $this->redirect(['/site']);

        $compareModel = new Wheel();
        if ($compareId > 0) {
            $compareModel = Wheel::findOne(['id' => $compareId]);
        }

        $wheels = Wheel::browse($model->person->id);

        if ($model->id == 0)
            return $this->redirect(['form', 'id' => 0]);
        else
            return $this->render('view', [
                        'model' => $model,
                        'compare' => $compareModel,
                        'wheels' => $wheels,
            ]);
    }

    public function actionRun()
    {
        $this->layout = 'printable';
        $showMissingAnswers = false;
        $current_dimension = 0;
        $token = Yii::$app->request->get('token');

        if (Yii::$app->request->isGet) {
            if ($token == null) {
                return $this->redirect(['/site', 'nowheel' => 1]);
            }

            $wheels = Wheel::findAll(['token' => $token]);

            if (count($wheels) == 0) {
                return $this->redirect(['/site', 'nowheel' => 1]);
            }
            $current_wheel = null;

            foreach ($wheels as $wheel) {
                if ($wheel->AnswerStatus == '0%') {
                    $current_dimension = -1;
                    $current_wheel = $wheel;
                    break;
                } else if ($wheel->AnswerStatus != '100%') {
                    $questionCount = WheelQuestion::getQuestionCount($wheel->type);
                    $setSize = $questionCount / 8;
                    $current_dimension = intval(count($wheel->answers) / $setSize);
                    $current_wheel = $wheel;
                    break;
                }
            }
        } else if (Yii::$app->request->isPost) {
            $current_dimension = Yii::$app->request->post('current_dimension');
            $id = Yii::$app->request->post('id');
            $current_wheel = Wheel::findOne(['id' => $id]);
            $questions = WheelQuestion::getQuestions($current_wheel->type);
            $questionCount = count($questions);
            $setSize = $questionCount / 8;

            $count = 0;

            for ($i = 0; $i < $questionCount; $i++) {
                $new_answer_value = Yii::$app->request->post('answer' . $i);

                if (isset($new_answer_value)) {
                    $count += 1;
                    $answer = null;
                    foreach ($current_wheel->answers as $lookup_answer)
                        if ($lookup_answer->answer_order == $i) {
                            $answer = $lookup_answer;
                            break;
                        }

                    if (isset($answer)) {
                        $answer->answer_order = $i;
                        $answer->answer_value = $new_answer_value;
                        $answer->dimension = $current_dimension;
                        $answer->question_id = $questions[$i]->question_id;
                        $answer->save();
                    } else {
                        $new_answer = new WheelAnswer();
                        $new_answer->answer_order = $i;
                        $new_answer->answer_value = $new_answer_value;
                        $new_answer->dimension = $current_dimension;
                        $new_answer->question_id = $questions[$i]->question_id;
                        $current_wheel->link('answers', $new_answer, ['wheel_id', 'id']);
                    }
                }
            }

            if ($current_dimension == -1 || $count == $setSize)
                $current_dimension += 1;
            else {
                \Yii::$app->session->addFlash('error', \Yii::t('wheel', 'Some answers missed'));
                $showMissingAnswers = true;
            }

            if ($current_wheel->validate()) {
                $current_wheel->save();
                if (count($current_wheel->answers) == $questionCount) {
                    if (Yii::$app->params['send_wheel_answers'] == true)
                        $this->sendAnswers($current_wheel);

                    $type_text = Wheel::getWheelTypes()[$current_wheel->type];

                    $text = Yii::t('wheel', '{wheel_type} of {observer} observing {observed} completed.', ['wheel_type' => $type_text, 'observer' => $current_wheel->observer->fullname, 'observed' => $current_wheel->observed->fullname]);
                    LogController::log($text, $current_wheel->coach->id);
                    return $this->redirect(['/wheel/run', 'token' => $token]);
                }
            }
        }

        if (!isset($current_wheel))
            return $this->render('thanks');
        else if ($current_dimension == -1)
            return $this->render('intro', [
                        'wheel' => $current_wheel,
                        'current_dimension' => $current_dimension,
            ]);

        Yii::$app->session->set('instructions_shown', true);
        return $this->render('form', [
                    'wheel' => $current_wheel,
                    'current_dimension' => $current_dimension,
                    'showMissingAnswers' => $showMissingAnswers,
        ]);
    }

    public function actionDelete($id)
    {
        $wheel = Wheel::findOne(['id' => $id]);
        if ($wheel->delete()) {
            SiteController::addFlash('success', Yii::t('wheel', 'Wheel deleted.'));
        } else {
            SiteController::FlashErrors($wheel);
        }
        return $this->redirect(['/person/view', 'id' => $wheel->person->id]);
    }

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

            SiteController::addFlash('success', Yii::t('wheel', 'Wheel questions saved.'));
            return $this->redirect(['/site']);
        }

        return $this->render('questions', [
                    'questions' => $wheelQuestions
        ]);
    }

    public function actionManualForm($id)
    {
        $wheel = Wheel::findOne(['id' => $id]);
        $invalids = [];

        if (Yii::$app->request->isPost) {
            $questions = WheelQuestion::getQuestions($wheel->type);
            $questionCount = count($questions);
            $setSize = $questionCount / 8;

            for ($i = 0; $i < $questionCount; $i++) {
                $valid_answer = true;
                $new_answer_value = Yii::$app->request->post('answer' . $i);
                if ($new_answer_value == '') {
                    $invalids[] = $i;
                    $valid_answer = false;
                }

                $new_answer_value = intval($new_answer_value);

                if ($new_answer_value < 0 || $new_answer_value > 4) {
                    $invalids[] = $i;
                    $valid_answer = false;
                }

                if ($valid_answer) {
                    $answer = null;
                    foreach ($wheel->answers as $lookup_answer)
                        if ($lookup_answer->answer_order == $i) {
                            $answer = $lookup_answer;
                            break;
                        }

                    if (isset($answer)) {
                        $answer->answer_order = $i;
                        $answer->answer_value = $new_answer_value;
                        $answer->dimension = intval($i / $setSize);
                        $answer->question_id = $questions[$i]->question_id;
                        if ($valid_answer)
                            $answer->save();
                    } else {
                        $new_answer = new WheelAnswer();
                        $new_answer->answer_order = $i;
                        $new_answer->answer_value = $new_answer_value;
                        $new_answer->dimension = intval($i / $setSize);
                        $new_answer->question_id = $questions[$i]->question_id;
                        if ($valid_answer)
                            $wheel->link('answers', $new_answer, ['wheel_id', 'id']);
                    }
                }
            }
            if (count($invalids) == 0) {
                SiteController::addFlash('success', Yii::t('wheel', 'Wheel answers saved.'));
                return $this->redirect(['/assessment/view', 'id' => $wheel->assessment->id]);
            } else {
                \Yii::$app->session->addFlash('error', \Yii::t('wheel', 'Some answers missed'));
            }
        }

        return $this->render('manual-form', [
                    'wheel' => $wheel,
                    'invalids' => $invalids,
        ]);
    }

    public function sendAnswers($wheel)
    {
        $type_text = Wheel::getWheelTypes()[$wheel->type];
        $questions = WheelQuestion::find()->where('type = ' . $wheel->type)->asArray()->all();

        Yii::$app->mailer->compose('answers', [
                    'wheel' => $wheel,
                    'questions' => $questions,
                ])
                ->setSubject(Yii::t('wheel', 'CPC: {wheel} answers', [
                            'wheel' => $type_text
                ]))
                ->setFrom($wheel->coach->email)
                ->setTo($wheel->observer->email)
                ->send();

        Yii::$app->mailer->compose('answers', [
                    'wheel' => $wheel,
                    'questions' => $questions,
                ])
                ->setSubject(Yii::t('wheel', "CPC: {wheel} answers of {person}", [
                            'wheel' => $type_text, 'person' => $wheel->observer->fullname
                ]))
                ->setFrom($wheel->observer->email)
                ->setTo($wheel->coach->email)
                ->send();
    }

}
