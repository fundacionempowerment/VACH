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
use app\models\Assessment;
use app\models\AssessmentAnswer;
use app\models\AssessmentQuestion;
use app\models\Wheel;

class AssessmentController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        $assessments = Assessment::find();

        return $this->render('index', [
        ]);
    }

    public function actionView($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        return $this->render('view', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionDelete($id) {
        $assessment = Assessment::findOne(['id' => $id]);
        $teamId = $assessment->team->id;
        if ($assessment->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('assessment', 'Assessment deleted.'));
        } else {
            SiteController::FlashErrors($assessment);
        }
        return $this->redirect(['/team/view', 'id' => $teamId]);
    }

    public function actionSendIndividual($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        foreach ($assessment->team->members as $teamMember) {
            $newWheel = new Wheel();

            $newWheel->observer_id = $teamMember->member->id;
            $newWheel->observed_id = $teamMember->member->id;
            $newWheel->type = Wheel::TYPE_INDIVIDUAL;
            $newWheel->token = $this->newToken();
            $newWheel->assessment_id = $assessment->id;

            if ($newWheel->save())
                $this->sendWheel($observerMember->member, $newWheel->token, Wheel::TYPE_INDIVIDUAL);
        }

        $assessment->individual_status = Assessment::STATUS_SENT;
        $assessment->save();

        return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
    }

    public function actionSendGroup($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        foreach ($assessment->team->members as $observerMember) {
            $token = $this->newToken();

            foreach ($assessment->team->members as $observedMember) {
                $newWheel = new Wheel();

                $newWheel->observer_id = $observerMember->member->id;
                $newWheel->observed_id = $observedMember->member->id;
                $newWheel->type = Wheel::TYPE_GROUP;
                $newWheel->token = $token;
                $newWheel->assessment_id = $assessment->id;

                $newWheel->save();
            }

            $this->sendWheel($observerMember->member, $token, Wheel::TYPE_GROUP);
        }

        $assessment->group_status = Assessment::STATUS_SENT;
        $assessment->save();

        return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
    }

    public function actionSendOrganizational($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        foreach ($assessment->team->members as $observerMember) {
            $token = $this->newToken();

            foreach ($assessment->team->members as $observedMember) {
                $newWheel = new Wheel();

                $newWheel->observer_id = $observerMember->member->id;
                $newWheel->observed_id = $observedMember->member->id;
                $newWheel->type = Wheel::TYPE_ORGANIZATIONAL;
                $newWheel->token = $token;
                $newWheel->assessment_id = $assessment->id;

                $newWheel->save();
            }

            $this->sendWheel($observerMember->member, $token, Wheel::TYPE_GROUP);
        }

        $assessment->organizational_status = Assessment::STATUS_SENT;
        $assessment->save();


        return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
    }

    public function actionViewGroup($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        return $this->render('view_group', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionViewOrganizational($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        return $this->render('view_organizational', [
                    'assessment' => $assessment,
        ]);
    }

    private static function newToken() {
        $number = rand(1000000000, 1999999999);
        $string = (string) $number;

        return $string[1] . $string[2] . $string[3] . '-' .
                $string[4] . $string[5] . $string[6] . '-' .
                $string[7] . $string[8] . $string[9];
    }

    private function sendWheel($member, $token, $type) {
        $type_text = Wheel::getWheelTypes()[$type];
        Yii::$app->mailer->compose('wheel', [
                    'token' => $token,
                    'type' => $type
                ])
                ->setSubject(Yii::t('assessment', "CPC: $type_text link"))
                ->setFrom(Yii::$app->user->identity->email)
                ->setTo($member->email)
                ->send();
    }

}

