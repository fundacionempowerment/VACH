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

        $groupWheel = [];
        $organizationalWheel = [];

        $individualPerformanceMatrix = [];
        $groupPerformanceMatrix = [];
        $organizationalPerformanceMatrix = [];

        $memberRelationMatrix = [];
        $groupRelationsMatrix = [];
        $organizationalRelationsMatrix = [];

        $individualEmergents = [];
        $groupEmergents = [];
        $organizationalEmergents = [];

        if ($filter->memberId > 0 && $filter->wheelType == Wheel::TYPE_INDIVIDUAL) {

            $projectedIndividualWheel = Wheel::getProjectedIndividualWheel($filter->assessmentId, $filter->memberId);
            $projectedGroupWheel = Wheel::getProjectedGroupWheel($filter->assessmentId, $filter->memberId);
            $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($filter->assessmentId, $filter->memberId);
            $reflectedGroupWheel = Wheel::getReflectedGroupWheel($filter->assessmentId, $filter->memberId);
            $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($filter->assessmentId, $filter->memberId);

            $individualPerformanceMatrix = Wheel::getPerformanceMatrix($filter->assessmentId, Wheel::TYPE_INDIVIDUAL);

            $individualEmergents = Wheel::getMemberEmergents($filter->assessmentId, $filter->memberId, Wheel::TYPE_INDIVIDUAL);
        }

        if ($filter->assessmentId > 0 && $filter->wheelType == Wheel::TYPE_GROUP) {
            $groupPerformanceMatrix = Wheel::getGroupPerformanceMatrix($filter->assessmentId);
            $groupRelationsMatrix = Wheel::getRelationsMatrix($filter->assessmentId, $filter->wheelType);

            if ($filter->memberId > 0) {
                $groupWheel = Wheel::getMemberGroupWheel($filter->assessmentId, $filter->memberId);
                $groupEmergents = Wheel::getMemberEmergents($filter->assessmentId, $filter->memberId, Wheel::TYPE_GROUP);

                foreach ($groupRelationsMatrix as $relation)
                    if ($relation['observed_id'] == $filter->memberId) {
                        $memberRelationMatrix[] = $relation;
                    }
            } else {
                $groupWheel = Wheel::getGroupWheel($filter->assessmentId);
                $groupEmergents = Wheel::getEmergents($filter->assessmentId, Wheel::TYPE_GROUP);
            }
        }

        if ($filter->assessmentId > 0 && $filter->wheelType == Wheel::TYPE_ORGANIZATIONAL) {
            $organizationalPerformanceMatrix = Wheel::getOrganizationalPerformanceMatrix($filter->assessmentId);
            $organizationalRelationsMatrix = Wheel::getRelationsMatrix($filter->assessmentId, $filter->wheelType);

            if ($filter->memberId > 0) {
                $organizationalWheel = Wheel::getMemberOrganizationalWheel($filter->assessmentId, $filter->memberId);
                $organizationalEmergents = Wheel::getMemberEmergents($filter->assessmentId, $filter->memberId, Wheel::TYPE_ORGANIZATIONAL);

                foreach ($organizationalRelationsMatrix as $relation)
                    if ($relation['observed_id'] == $filter->memberId) {
                        $memberRelationMatrix[] = $relation;
                    }
            } else {
                $organizationalWheel = Wheel::getOrganizationalWheel($filter->assessmentId);
                $organizationalEmergents = Wheel::getEmergents($filter->assessmentId, Wheel::TYPE_ORGANIZATIONAL);
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
                    'individualEmergents' => $individualEmergents,
                    // group wheel
                    'groupPerformanceMatrix' => $groupPerformanceMatrix,
                    'groupWheel' => $groupWheel,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'memberRelationMatrix' => $memberRelationMatrix,
                    'groupEmergents' => $groupEmergents,
                    // organizational
                    'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                    'organizationalWheel' => $organizationalWheel,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'organizationalEmergents' => $organizationalEmergents,
        ]);
    }

}

