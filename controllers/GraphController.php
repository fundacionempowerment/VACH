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
use app\models\Company;
use app\models\DashboardFilter;
use app\models\Person;
use app\models\WheelQuestion;

class GraphController extends BaseController
{

    public function actionRadar($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Radar::draw($teamId, $memberId, $wheelType);
    }

    public function actionLineal($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Lineal::draw($teamId, $memberId, $wheelType);
    }

    public function actionMatrix($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Matrix::draw($teamId, $memberId, $wheelType);
    }

    public function actionRelations($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Relations::draw($teamId, $memberId, $wheelType);
    }

    public function actionGauges($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Gauges::draw($teamId, $memberId, $wheelType);
    }

    public function actionEmergents($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Emergents::draw($teamId, $memberId, $wheelType);
    }

    private function checkAllowed($teamId)
    {
        if (Yii::$app->request->get('t')) {
            $session = \app\models\UserSession::findOne(['token' => Yii::$app->request->get('t')]);
        } else {
            $session = \app\models\UserSession::findOne(['token' => session_id()]);
        }

        $team = Team::findOne(['id' => $teamId]);

        if ($team->coach_id == $session->user_id) {
            return true;
        }

        foreach ($team->teamCoaches as $teamCoach) {
            if ($teamCoach->coach->id == $session->user_id) {
                return true;
            }
        }

        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
    }

}
