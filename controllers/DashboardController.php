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

class DashboardController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        $companyId = Yii::$app->request->post('companyId')? : 0;
        $companies = ArrayHelper::map(Company::browse()->asArray()->all(), 'id', 'name');
        $companies[0] = Yii::t('app', 'All');

        $teamId = Yii::$app->request->post('teamId')? : 0;
        if ($companyId > 0) {
            $teams = ArrayHelper::map(Team::findAll(['company_id' => $companyId]), 'id', 'name');
        }
        $teams[0] = Yii::t('app', 'All');

        $assessmentId = Yii::$app->request->post('assessmentId')? : 0;
        if ($teamId > 0) {
            $assessments = ArrayHelper::map(Assessment::findAll(['team_id' => $teamId]), 'id', 'name');
        }
        $assessments[0] = Yii::t('app', 'All');

        $memberId = Yii::$app->request->post('memberId')? : 0;
        if ($teamId > 0) {
            foreach (TeamMember::findAll(['team_id' => $teamId]) as $teamMember)
                $members[$teamMember->user_id] = $teamMember->member->fullname;
        }
        $members[0] = Yii::t('app', 'All');

        $wheelType = Yii::$app->request->post('wheelType')? : 0;

        $individualWheel = [];
        if ($memberId > 0 && $wheelType == Wheel::TYPE_INDIVIDUAL) {
            $individualWheel = Wheel::getIndividualWheel($assessmentId, $memberId);
        }

        $projectedGroupWheel = [];
        if ($memberId > 0 && $wheelType == Wheel::TYPE_INDIVIDUAL) {
            $projectedGroupWheel = Wheel::getProjectedGroupWheel($assessmentId, $memberId);
        }


        $projectedOrganizationalWheel = [];
        if ($memberId > 0 && $wheelType == Wheel::TYPE_INDIVIDUAL) {
            $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($assessmentId, $memberId);
        }

        return $this->render('index', [
                    'companyId' => $companyId,
                    'companies' => $companies,
                    'teamId' => $teamId,
                    'teams' => $teams,
                    'assessmentId' => $assessmentId,
                    'assessments' => $assessments,
                    'memberId' => $memberId,
                    'members' => $members,
                    'wheelType' => $wheelType,
                    'individualWheel' => $individualWheel,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
        ]);
    }

}

