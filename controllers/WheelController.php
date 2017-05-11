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
                if (count($current_wheel->answers) == $questionCount) {
                    $current_wheel->status = 'done';
                    $current_wheel->save();
                    $type_text = Wheel::getWheelTypes()[$current_wheel->type];

                    $text = Yii::t('wheel', '{wheel_type} of {observer} observing {observed} completed.', ['wheel_type' => $type_text, 'observer' => $current_wheel->observer->fullname, 'observed' => $current_wheel->observed->fullname]);
                    LogController::log($text, $current_wheel->coach->id);
                    return $this->redirect(['/wheel/run', 'token' => $token]);
                } else {
                    $current_wheel->status = 'in_progress';
                    $current_wheel->save();
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

    public function actionManualForm($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $wheel = Wheel::findOne(['id' => $id]);

        if (!$wheel || !$wheel->assessment->isUserAllowed()) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

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

    public function actionReceived($token)
    {
        $this->layout = 'printable';

        $wheels = Wheel::find()
                ->where(['token' => $token])
                ->andWhere(['in', 'status', ['created', 'sent']])
                ->all();

        foreach ($wheels as $wheel) {
            $wheel->status = 'received';
            $wheel->save();
        }

        return $this->render('received', [
                    'wheels' => $wheels,
        ]);
    }

}
