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

class TeamController extends BaseController {

    public $layout = 'inner';

    public function actionIndex() {
        if (Yii::$app->user->isGuest)
            return $this->redirect(['/site']);

        $teams = Team::browse();

        return $this->render('index', [
                    'teams' => $teams,
        ]);
    }

    public function actionView($id) {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        if (Yii::$app->request->isPost) {
            $new_member_id = Yii::$app->request->post('new_member');

            $teamMember = new TeamMember();
            $teamMember->user_id = $new_member_id;
            $teamMember->team_id = $team->id;
            if ($teamMember->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully added to {group}.', ['name' => $teamMember->member->fullname, 'group' => $team->fullname]));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            } else
                SiteController::FlashErrors($teamMember);
        }

        return $this->render('view', [
                    'team' => $team,
                    'persons' => $this->getPersons(),
        ]);
    }

    public function actionNew() {
        $team = new Team();

        if ($team->load(Yii::$app->request->post()) && $team->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $team->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        } else {
            SiteController::FlashErrors($team);
        }

        return $this->render('form', [
                    'team' => $team,
                    'companies' => $this->getCompanies(),
                    'persons' => $this->getPersons(),
        ]);
    }

    public function actionEdit($id) {
        $team = Team::findOne(['id' => $id]);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        Yii::$app->session->set('team_id', $id);

        if ($team->load(Yii::$app->request->post()) && $team->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $team->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        } else {
            SiteController::FlashErrors($team);
        }

        return $this->render('form', [
                    'team' => $team,
                    'companies' => $this->getCompanies(),
                    'persons' => $this->getPersons(),
        ]);
    }

    private function save($team) {
        
    }

    public function actionDelete($id) {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        if ($team->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $team->name]));
        } else {
            SiteController::FlashErrors($team);
        }
        return $this->redirect(['/team']);
    }

    public function actionFullfilled($id) {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        $team->blocked = true;
        $team->save();

        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    public function actionNewMember($id) {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        $member = new Person();

        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            $teamMember = new TeamMember();
            $teamMember->user_id = $member->id;
            $teamMember->team_id = $team->id;
            $teamMember->save();
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $member->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        } else
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
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $member->fullname]));
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
        $member = $teamMember->member;

        if ($teamMember->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully removed from {group}.', ['name' => $member->fullname, 'group' => $team->fullname]));
        } else {
            SiteController::FlashErrors($teamMember);
        }
        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    public function actionDeleteAssessment($id) {
        $assessment = Assessment::findOne($id);
        $team = $assessment->team;

        if (Yii::$app->request->post('delete')) {
            if ($assessment->delete()) {

                SiteController::addFlash('success', Yii::t('assessment', 'Assessment has been successfully deleted.'));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            } else {
                SiteController::FlashErrors($assessment);
            }
        }

        return $this->render('delete_assessment', [
                    'assessment' => $assessment,
                    'team' => $team,
        ]);
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
