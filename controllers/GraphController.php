<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use app\models\Wheel;
use app\models\TeamMember;
use app\models\Team;
use app\models\Assessment;
use app\models\Company;
use app\models\DashboardFilter;
use app\models\Person;
use app\models\WheelQuestion;

class GraphController extends BaseController
{

    public function actionRadar($assessmentId, $memberId, $wheelType)
    {
        $this->checkAllowed($assessmentId);
        \app\components\graph\Radar::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionLineal($assessmentId, $memberId, $wheelType)
    {
        $this->checkAllowed($assessmentId);
        \app\components\graph\Lineal::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionMatrix($assessmentId, $memberId, $wheelType)
    {
        $this->checkAllowed($assessmentId);
        \app\components\graph\Matrix::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionRelations($assessmentId, $memberId, $wheelType)
    {
        $this->checkAllowed($assessmentId);
        \app\components\graph\Relations::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionGauges($assessmentId, $memberId, $wheelType)
    {
        $this->checkAllowed($assessmentId);
        \app\components\graph\Gauges::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionEmergents($assessmentId, $memberId, $wheelType)
    {
        $this->checkAllowed($assessmentId);
        \app\components\graph\Emergents::draw($assessmentId, $memberId, $wheelType);
    }

    private function checkAllowed($assessmentId)
    {
        if (Yii::$app->request->get('t')) {
            $session = \app\models\UserSession::findOne(['token' => Yii::$app->request->get('t')]);
        } else {
            $session = \app\models\UserSession::findOne(['token' => session_id()]);
        }

        $assessment = Assessment::findOne(['id' => $assessmentId]);

        if ($assessment->team->coach_id == $session->user_id) {
            return true;
        }

        foreach ($assessment->assessmentCoaches as $assessmentCoach) {
            if ($assessmentCoach->coach->id == $session->user_id) {
                return true;
            }
        }

        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
    }

}
