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

class DashboardController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        SiteController::checkUserSession();

        $filter = Yii::$app->session->get('DashboardFilter') ?: new DashboardFilter();
        if ($filter->load(Yii::$app->request->post())) {
            Yii::$app->session->set('DashboardFilter', $filter);
            $this->redirect(['/dashboard']);
        }

        $companies = [];
        $teams = [];
        $team = null;
        $members = [];
        $member = null;

        $companies = Company::getDashboardList();
        if (count($companies) == 1) {
            foreach ($companies as $id => $fullname) {
                $filter->companyId = $id;
                break;
            }
        }

        if ($filter->memberId > 0) {
            $member = Person::findOne(['id' => $filter->memberId]);
        }

        if ($filter->companyId > 0) {
            $teams = Team::getDashboardList($filter->companyId);

            if (count($teams) == 1) {
                foreach ($teams as $id => $fullname) {
                    $filter->teamId = $id;
                    break;
                }
            } else {
                $exists = false;
                foreach ($teams as $id => $fullname)
                    if ($id == $filter->teamId) {
                        $exists = true;
                        break;
                    }

                if (!$exists) {
                    $filter->teamId = 0;
                    $filter->teamId = 0;
                }
            }
        }

        if ($filter->teamId > 0) {
            $team = Team::findOne(['id' => $filter->teamId]);
            foreach (TeamMember::find()->where(['team_id' => $filter->teamId, 'active' => true])->all() as $teamMember)
                $members[$teamMember->person_id] = $teamMember->member->fullname;
        }

        $members[0] = Yii::t('app', 'All');

        $projectedIndividualWheel = [];
        $projectedGroupWheel = [];
        $projectedOrganizationalWheel = [];
        $reflectedGroupWheel = [];
        $reflectedOrganizationalWheel = [];

        $individualPerformanceMatrix = [];
        $performanceMatrix = [];

        $relationsMatrix = [];

        $gauges = [];
        $emergents = [];

        if ($filter->memberId > 0 && $filter->wheelType == Wheel::TYPE_INDIVIDUAL) {

            $projectedIndividualWheel = Wheel::getProjectedIndividualWheel($filter->teamId, $filter->memberId);
            $projectedGroupWheel = Wheel::getProjectedGroupWheel($filter->teamId, $filter->memberId);
            $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($filter->teamId, $filter->memberId);
            $reflectedGroupWheel = Wheel::getReflectedGroupWheel($filter->teamId, $filter->memberId);
            $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($filter->teamId, $filter->memberId);

            $emergents = Wheel::getMemberEmergents($filter->teamId, $filter->memberId, Wheel::TYPE_INDIVIDUAL);
        } else if ($filter->teamId > 0 && $filter->wheelType > 0) {
            $performanceMatrix = Wheel::getPerformanceMatrix($filter->teamId, $filter->wheelType);
            $relationsMatrix = Wheel::getRelationsMatrix($filter->teamId, $filter->wheelType);

            if ($filter->memberId > 0) {
                $gauges = Wheel::getMemberGauges($filter->teamId, $filter->memberId, $filter->wheelType);
                $emergents = Wheel::getMemberEmergents($filter->teamId, $filter->memberId, $filter->wheelType);
            } else {
                $gauges = Wheel::getGauges($filter->teamId, $filter->wheelType);
                $emergents = Wheel::getEmergents($filter->teamId, $filter->wheelType);
            }
        }

        return $this->render('index', [
                    'filter' => $filter,
                    'companies' => $companies,
                    'teams' => $teams,
                    'team' => $team,
                    'members' => $members,
                    'member' => $member,
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
                    'emergents' => $emergents,
        ]);
    }

}
