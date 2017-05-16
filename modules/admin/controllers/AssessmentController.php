<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Assessment;
use app\models\DashboardFilter;
use app\models\Wheel;
use app\models\Stock;
use app\models\AssessmentCoach;

class AssessmentController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $assessments = Assessment::browse();

        return $this->render('index', [
                    'assessments' => $assessments,
        ]);
    }

    public function actionView($id)
    {
        $assessment = Assessment::find()
                ->where(['id' => $id])
                ->one();

        return $this->render('view', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionNew($teamId)
    {
        $assessment = new Assessment();
        $assessment->team_id = $teamId;

        $balance = Stock::getStock(1);
        $licences_required = count($assessment->team->members);
        $licences_to_buy = $licences_required - $balance;

        if (!Yii::$app->params['monetize'] || $licences_to_buy <= 0) {
            if ($assessment->load(Yii::$app->request->post()) && $assessment->save()) {
                foreach ($assessment->team->members as $observerMember) {
                    $token = Wheel::getNewToken();
                    $newWheel = new Wheel();

                    $newWheel->observer_id = $observerMember->member->id;
                    $newWheel->observed_id = $observerMember->member->id;
                    $newWheel->type = Wheel::TYPE_INDIVIDUAL;
                    $newWheel->token = $token;
                    $newWheel->assessment_id = $assessment->id;

                    $newWheel->save();

                    $token = Wheel::getNewToken();
                    foreach ($assessment->team->members as $observedMember) {
                        $newWheel = new Wheel();
                        $newWheel->observer_id = $observerMember->member->id;
                        $newWheel->observed_id = $observedMember->member->id;
                        $newWheel->type = Wheel::TYPE_GROUP;
                        $newWheel->token = $token;
                        $newWheel->assessment_id = $assessment->id;
                        $newWheel->save();
                    }

                    $token = Wheel::getNewToken();
                    foreach ($assessment->team->members as $observedMember) {
                        $newWheel = new Wheel();
                        $newWheel->observer_id = $observerMember->member->id;
                        $newWheel->observed_id = $observedMember->member->id;
                        $newWheel->type = Wheel::TYPE_ORGANIZATIONAL;
                        $newWheel->token = $token;
                        $newWheel->assessment_id = $assessment->id;
                        $newWheel->save();
                    }
                }
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $assessment->fullname]));

                if (Yii::$app->params['monetize']) {
                    $stock = new Stock();
                    $stock->coach_id = Yii::$app->user->id;
                    $stock->creator_id = Yii::$app->user->id;
                    $stock->product_id = 1;
                    $stock->quantity = -$licences_required;
                    $stock->price = 0;
                    $stock->total = 0;
                    $stock->status = Stock::STATUS_VALID;
                    if (!$stock->save()) {
                        \app\controllers\SiteController::FlashErrors($stock);
                    }
                }
                return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
            } else {
                SiteController::FlashErrors($assessment);
            }
        }

        return $this->render('form', [
                    'assessment' => $assessment,
                    'balance' => $balance,
                    'licences_required' => $licences_required,
                    'licences_to_buy' => $licences_to_buy,
        ]);
    }

    public function actionDelete($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $teamId = $assessment->team->id;
        if ($assessment->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $assessment->fullname]));
        } else {
            SiteController::FlashErrors($assessment);
        }
        return $this->redirect(['/team/view', 'id' => $teamId]);
    }

    public function actionSendWheel($id, $memberId, $type)
    {
        $assessment = Assessment::findOne(['id' => $id]);

        $sent = false;
        foreach ($assessment->team->members as $teamMember) {
            if ($teamMember->person_id == $memberId) {
                $wheels = [];
                switch ($type) {
                    case Wheel::TYPE_INDIVIDUAL:
                        $wheels = $assessment->individualWheels;
                        break;
                    case Wheel::TYPE_GROUP:
                        $wheels = $assessment->groupWheels;
                        break;
                    default :
                        $wheels = $assessment->organizationalWheels;
                        break;
                }

                foreach ($wheels as $wheel)
                    if ($wheel->observer_id == $memberId && $wheel->answerStatus != '100%') {
                        $this->sendWheel($wheel);
                        $sent = true;
                        break;
                    }
            }
        }

        if ($sent == false) {
            \Yii::$app->session->addFlash('info', \Yii::t('assessment', 'Wheel already fullfilled. Email not sent.'));
        }
        return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
    }

    public function actionDetailView($id, $type)
    {
        $assessment = Assessment::findOne(['id' => $id]);

        return $this->render('detail_view', [
                    'assessment' => $assessment,
                    'type' => $type,
        ]);
    }

    public function actionToggleAutofill($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);

        $assessment->autofill_answers = !$assessment->autofill_answers;
        $assessment->save();

        return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
    }

    public function actionGoToDashboard($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $filter = new DashboardFilter();

        $filter->companyId = $assessment->team->company_id;
        $filter->teamId = $assessment->team->id;
        $filter->assessmentId = $id;
        $filter->wheelType = Wheel::TYPE_GROUP;

        Yii::$app->session->set('DashboardFilter', $filter);
        $this->redirect(['/dashboard']);
    }

    public function actionGrantCoach($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $coach_id = Yii::$app->request->post('coach_id');

        //if ($assessment->coach_id == Yii::$app->user->identity->id && $coach_id) {
        if ($coach_id) {
            if (AssessmentCoach::notGranted($id, $coach_id)) {
                $model = new AssessmentCoach([
                    'assessment_id' => $id,
                    'coach_id' => $coach_id,
                ]);
                if ($model->save()) {
                    SiteController::addFlash('success', Yii::t('assessment', 'Access granted'));
                }
            }
        }

        return $this->redirect(['/assessment/view', 'id' => $id]);
    }

    public function actionRemoveCoach($id)
    {
        $assessmentCoach = AssessmentCoach::findOne(['id' => $id]);
        $assessmentCoach->delete();

        SiteController::addFlash('success', Yii::t('assessment', 'Access removed'));

        return $this->redirect(['/assessment/view', 'id' => $assessmentCoach->assessment_id]);
    }

    private function sendWheel($wheel)
    {
        $wheel_type = Wheel::getWheelTypes()[$wheel->type];
        Yii::$app->mailer->compose('wheel', [
                    'wheel' => $wheel,
                ])
                ->setSubject(Yii::t('assessment', 'CPC: access to {wheel_type} of assessment {assessment}', [
                            'wheel_type' => $wheel_type,
                            'assessment' => $wheel->assessment->name,
                ]))
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($wheel->observer->email)
                ->setBcc(Yii::$app->params['adminEmail'])
                ->setReplyTo(Yii::$app->params['adminEmail'])
                ->send();

        SiteController::addFlash('success', \Yii::t('assessment', '{wheel_type} sent to {user}.', ['wheel_type' => $wheel_type, 'user' => $wheel->observer->fullname]));

        $wheels = Wheel::find()->where(['token' => $wheel->token])->all();
        foreach ($wheels as $wheel) {
            if ($wheel->status == 'created') {
                $wheel->status = 'sent';
                $wheel->save();
            }
        }
    }

}
