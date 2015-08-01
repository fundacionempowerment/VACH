<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\CoachModel;
use app\models\ClientModel;
use app\models\Team;
use app\models\TeamMember;
use app\models\Company;
use app\models\Person;
use app\models\Assessment;

class TeamController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        $teams = Team::browse();

        return $this->render('index', [
                    'teams' => $teams,
        ]);
    }

    public function actionView($id) {
        $team = Team::findOne($id);

        if (Yii::$app->request->isPost) {
            $new_member_id = Yii::$app->request->post('new_member');

            $teamMember = new TeamMember();
            $teamMember->user_id = $new_member_id;
            $teamMember->team_id = $team->id;
            if ($teamMember->save()) {
                \Yii::$app->session->addFlash('success', \Yii::t('team', 'Team member has been succesfully created.'));
                $this->redirect(['/team/view', 'id' => $team->id]);
            }
            else
                SiteController::FlashErrors($teamMember);
        }

        return $this->render('view', [
                    'team' => $team,
                    'persons' => $this->getPersons(),
        ]);
    }

    public function actionNew() {
        $team = new Team();

        $this->save($team);

        return $this->render('form', [
                    'team' => $team,
                    'companies' => $this->getCompanies(),
                    'persons' => $this->getPersons(),
        ]);
    }

    public function actionEdit($id) {
        $team = Team::findOne(['id' => $id]);

        Yii::$app->session->set('team_id', $id);

        $this->save($team);
        return $this->render('form', [
                    'team' => $team,
                    'companies' => $this->getCompanies(),
                    'persons' => $this->getPersons(),
        ]);
    }

    private function save($team) {
        if ($team->load(Yii::$app->request->post()) && $team->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('team', 'Team has been succesfully created.'));
            $this->redirect(['/team/view', 'id' => $team->id]);
        } else {
            SiteController::FlashErrors($team);
        }
    }

    public function actionDelete($id) {
        $team = Team::findOne($id);
        if ($team->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('team', 'Team deleted.'));
        } else {
            SiteController::FlashErrors($team);
        }
        return $this->redirect(['/team']);
    }

    public function actionNewMember($id) {
        $team = Team::findOne($id);

        $member = new Person();

        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            $teamMember = new TeamMember();
            $teamMember->user_id = $member->id;
            $teamMember->team_id = $team->id;
            $teamMember->save();
            \Yii::$app->session->addFlash('success', \Yii::t('team', 'Team member has been succesfully created.'));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        }
        else
            SiteController::FlashErrors($member);

        return $this->render('member-form', [
                    'team' => $team,
                    'member' => $member,
        ]);
    }

    public function actionEditMember($id) {
        $teamMember = TeamMember::findOne($id);

        $team = $teamMember->team;
        $member = $teamMember->member;

        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('team', 'Team member has been succesfully saved.'));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        }
        return $this->render('member-form', [
                    'team' => $team,
                    'member' => $member,
        ]);
    }

    public function actionDeleteMember($id) {
        $teamMember = TeamMember::findOne($id);
        $team = $teamMember->team;

        if ($teamMember->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('team', 'Team member has been succesfully deleted.'));
        } else {
            SiteController::FlashErrors($teamMember);
        }
        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    private function getCompanies() {
        return $companies = ArrayHelper::map(Company::browse()->asArray()->all(), 'id', 'name');
    }

    private function getPersons() {
        $persons = [];
        foreach (Person::browse()->all() as $person)
            $persons[$person->id] = $person->fullname;
        return $persons;
    }

}