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

class DashboardController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        $filter = Yii::$app->session->get('DashboardFilter') ? : new DashboardFilter();
        if ($filter->load(Yii::$app->request->post())) {
            Yii::$app->session->set('DashboardFilter', $filter);
            $this->redirect(['/dashboard']);
        }

        $companies = [];
        $teams = [];
        $assessments = [];
        $members = [];

        $companies = ArrayHelper::map(Company::browse()->asArray()->all(), 'id', 'name');

        if ($filter->companyId > 0) {
            $teams = ArrayHelper::map(Team::findAll(['company_id' => $filter->companyId]), 'id', 'name');
        }

        if ($filter->teamId > 0) {
            $assessments = ArrayHelper::map(Assessment::findAll(['team_id' => $filter->teamId]), 'id', 'name');

            foreach (TeamMember::findAll(['team_id' => $filter->teamId]) as $teamMember)
                $members[$teamMember->user_id] = $teamMember->member->fullname;
        }
        $members[0] = Yii::t('app', 'All');

        $projectedIndividualWheel = [];
        $projectedGroupWheel = [];
        $projectedOrganizationalWheel = [];
        $reflectedGroupWheel = [];
        $reflectedOrganizationalWheel = [];

        $gauges = [];

        $individualPerformanceMatrix = [];
        $performanceMatrix = [];

        $memberRelationMatrix = [];
        $relationsMatrix = [];

        $emergents = [];

        if ($filter->memberId > 0 && $filter->wheelType == Wheel::TYPE_INDIVIDUAL) {

            $projectedIndividualWheel = Wheel::getProjectedIndividualWheel($filter->assessmentId, $filter->memberId);
            $projectedGroupWheel = Wheel::getProjectedGroupWheel($filter->assessmentId, $filter->memberId);
            $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($filter->assessmentId, $filter->memberId);
            $reflectedGroupWheel = Wheel::getReflectedGroupWheel($filter->assessmentId, $filter->memberId);
            $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($filter->assessmentId, $filter->memberId);

            $individualPerformanceMatrix = Wheel::getPerformanceMatrix($filter->assessmentId, Wheel::TYPE_INDIVIDUAL);

            $emergents = Wheel::getMemberEmergents($filter->assessmentId, $filter->memberId, Wheel::TYPE_INDIVIDUAL);
        } else if ($filter->assessmentId > 0) {
            $performanceMatrix = Wheel::getPerformanceMatrix($filter->assessmentId, $filter->wheelType);
            $relationsMatrix = Wheel::getRelationsMatrix($filter->assessmentId, $filter->wheelType);

            if ($filter->memberId > 0) {
                $gauges = Wheel::getMemberGauges($filter->assessmentId, $filter->memberId, $filter->wheelType);
                $emergents = Wheel::getMemberEmergents($filter->assessmentId, $filter->memberId, $filter->wheelType);

                foreach ($relationsMatrix as $relation)
                    if ($relation['observed_id'] == $filter->memberId) {
                        $memberRelationMatrix[] = $relation;
                    }
            } else {
                $gauges = Wheel::getGauges($filter->assessmentId, $filter->wheelType);
                $emergents = Wheel::getEmergents($filter->assessmentId, $filter->wheelType);
            }
        }

        return $this->render('index', [
                    'filter' => $filter,
                    'companies' => $companies,
                    'teams' => $teams,
                    'assessments' => $assessments,
                    'members' => $members,
                    // Indivudual wheel
                    'projectedIndividualWheel' => $projectedIndividualWheel,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                    'reflectedGroupWheel' => $reflectedGroupWheel,
                    'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
                    'individualPerformanceMatrix' => $individualPerformanceMatrix,
                    // group wheel
                    'performanceMatrix' => $performanceMatrix,
                    'gauges' => $gauges,
                    'relationsMatrix' => $relationsMatrix,
                    'memberRelationMatrix' => $memberRelationMatrix,
                    'emergents' => $emergents,
        ]);
    }

}

