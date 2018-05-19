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

class GraphController extends Controller
{
    public function actionRadar($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Radar::draw($teamId, $memberId, $wheelType);
    }

    public function actionPerceptions($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Perception::draw($teamId, $memberId, $wheelType);
    }

    public function actionPerformance($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Performance::draw($teamId, $memberId, $wheelType);
    }

    public function actionRelations($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Relations::draw($teamId, $memberId, $wheelType);
    }

    public function actionCompetences($teamId, $memberId, $wheelType)
    {
        $this->checkAllowed($teamId);
        \app\components\graph\Competences::draw($teamId, $memberId, $wheelType);
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

        if ($session && $team->coach_id == $session->user_id) {
            return true;
        }

        foreach ($team->teamCoaches as $teamCoach) {
            if ($session && $teamCoach->coach->id == $session->user_id) {
                return true;
            }
        }

        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
    }
}
