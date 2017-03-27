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
        \app\components\graph\Radar::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionLineal($assessmentId, $memberId, $wheelType)
    {
        \app\components\graph\Lineal::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionMatrix($assessmentId, $memberId, $wheelType)
    {
        \app\components\graph\Matrix::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionRelations($assessmentId, $memberId, $wheelType)
    {
        \app\components\graph\Relations::draw($assessmentId, $memberId, $wheelType);
    }

    public function actionGauges($assessmentId, $memberId, $wheelType)
    {
        \app\components\graph\Gauges::draw($assessmentId, $memberId, $wheelType);
    }

}
