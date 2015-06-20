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
        $filter = Yii::$app->session->get('DashboardFilte') ? : new DashboardFilter();
        if ($filter->load(Yii::$app->request->post())) {
            Yii::$app->session->set('DashboardFilte', $filter);
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

        if ($filter->assessmentId == 0 || ($filter->wheelType == Wheel::TYPE_INDIVIDUAL && $filter->memberId == 0)) {
            return $this->render('index', [
                        'filter' => $filter,
                        'companies' => $companies,
                        'teams' => $teams,
                        'assessments' => $assessments,
                        'members' => $members,
            ]);
        }

        if ($filter->wheelType == Wheel::TYPE_INDIVIDUAL) {

            $projectedIndividualWheel = Wheel::getProjectedIndividualWheel($filter->assessmentId, $filter->memberId);
            $projectedGroupWheel = Wheel::getProjectedGroupWheel($filter->assessmentId, $filter->memberId);
            $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($filter->assessmentId, $filter->memberId);
            $reflectedGroupWheel = Wheel::getReflectedGroupWheel($filter->assessmentId, $filter->memberId);
            $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($filter->assessmentId, $filter->memberId);

            $performanceMatrix = [
                ['name' => 'Marcelo', 'productivity' => 67.59, 'consciousness' => -12.44],
                ['name' => 'Maria', 'productivity' => 77.78, 'consciousness' => -9.34],
                ['name' => 'Mariana', 'productivity' => 79.34, 'consciousness' => -10.53]
            ];
            return $this->render('individual', [
                        'filter' => $filter,
                        'companies' => $companies,
                        'teams' => $teams,
                        'assessments' => $assessments,
                        'members' => $members,
                        'projectedIndividualWheel' => $projectedIndividualWheel,
                        'projectedGroupWheel' => $projectedGroupWheel,
                        'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                        'reflectedGroupWheel' => $reflectedGroupWheel,
                        'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
                        'performanceMatrix' => $performanceMatrix,
            ]);
        }


        if ($filter->wheelType == Wheel::TYPE_GROUP) {
            $reflectedGroupWheel = Wheel::getGroupWheel($assessmentId);

            return $this->render('individual', [
                        'filter' => $filter,
                        'companies' => $companies,
                        'teams' => $teams,
                        'assessments' => $assessments,
                        'members' => $members,
                        'groupWheel' => $groupWheel,
            ]);
        }
    }

}

