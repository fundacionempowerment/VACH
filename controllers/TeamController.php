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

class TeamController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $teams = Team::browse();

        return $this->render('index', [
                    'teams' => $teams,
        ]);
    }

    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $team = Team::findOne($id);

        if (!$team || $team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if (Yii::$app->request->isPost) {
            $new_member_id = Yii::$app->request->post('new_member');

            $teamMember = new TeamMember();
            $teamMember->person_id = $new_member_id;
            $teamMember->team_id = $team->id;
            if ($teamMember->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully added to {group}.', ['name' => $teamMember->member->fullname, 'group' => $team->fullname]));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            } else
                SiteController::FlashErrors($teamMember);
        }

        $persons = [];
        if (!$team->blocked) {
            $persons = $this->getPersons();
        }

        return $this->render('view', [
                    'team' => $team,
                    'persons' => $persons,
        ]);
    }

    public function actionNew()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

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

    public function actionEdit($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $team = Team::findOne(['id' => $id]);

        if (!$team || $team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
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

    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $team = Team::findOne($id);

        if (!$team || $team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($team->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $team->name]));
        } else {
            SiteController::FlashErrors($team);
        }
        return $this->redirect(['/team']);
    }

    public function actionFullfilled($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $team = Team::findOne($id);

        if (!$team || $team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        $team->blocked = true;

        if ($team->save()) {
            return $this->redirect(['assessment/new', 'teamId' => $team->id]);
        } else {
            SiteController::FlashErrors($team);
        }

        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    public function actionNewMember($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $team = Team::findOne($id);

        if (!$team || $team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        $member = new Person();

        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            $teamMember = new TeamMember();
            $teamMember->person_id = $member->id;
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

    public function actionEditMember($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $teamMember = TeamMember::findOne($id);

        $team = $teamMember->team;
        $member = $teamMember->member;

        if ($team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $member->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        }
        return $this->render('member-form', [
                    'team' => $team,
                    'member' => $member,
        ]);
    }

    public function actionDeleteMember($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $teamMember = TeamMember::findOne($id);
        $team = $teamMember->team;
        $member = $teamMember->member;

        if ($team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($teamMember->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully removed from {group}.', ['name' => $member->fullname, 'group' => $team->fullname]));
        } else {
            SiteController::FlashErrors($teamMember);
        }
        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    public function actionDeleteAssessment($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $assessment = Assessment::findOne($id);
        $team = $assessment->team;

        if ($team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

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

    public function actionActivateMember()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $id = Yii::$app->request->get("id");
        $isActive = Yii::$app->request->get("isActive");

        $teamMember = TeamMember::findOne(['id' => $id]);

        if ($teamMember->team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($teamMember) {
            $teamMember->active = $isActive;
            $teamMember->save(false);
            return 'ok';
        }

        return 'error';
    }

    private function getCompanies()
    {
        return $companies = ArrayHelper::map(Company::browse()->asArray()->all(), 'id', 'name');
    }

    private function getPersons()
    {
        $persons = [];
        foreach (Person::browse()->all() as $person)
            $persons[$person->id] = $person->fullname;
        return $persons;
    }

}
