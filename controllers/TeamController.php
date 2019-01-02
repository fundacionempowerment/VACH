<?php

namespace app\controllers;

use app\models\Company;
use app\models\DashboardFilter;
use app\models\LoginModel;
use app\models\Person;
use app\models\RegisterModel;
use app\models\search\TeamSearch;
use app\models\Stock;
use app\models\Team;
use app\models\TeamCoach;
use app\models\TeamMember;
use app\models\User;
use app\models\Wheel;
use Yii;
use yii\helpers\ArrayHelper;

class TeamController extends BaseController {

    public $layout = 'inner';

    public function actionIndex() {
        $searchModel = new TeamSearch();
        $teams = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'teams' => $teams,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id) {
        $team = Team::findOne($id);

        if (!$team || !$team->isUserAllowed()) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if (Yii::$app->request->isPost) {
            $new_member_id = Yii::$app->request->post('new_member');

            $teamMember = new TeamMember();
            $teamMember->person_id = $new_member_id;
            $teamMember->team_id = $team->id;
            $teamMember->active = true;

            if ($team->hasMember($new_member_id)) {
                SiteController::addFlash('error', Yii::t('app', '{name} has been already added to {group}.', ['name' => $teamMember->member->fullname, 'group' => $team->fullname]));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            }

            if ($teamMember->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully added to {group}.', ['name' => $teamMember->member->fullname, 'group' => $team->fullname]));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            } else {
                SiteController::FlashErrors($teamMember);
            }
        }

        $persons = $this->getPersons();

        return $this->render('view', [
            'team' => $team,
            'persons' => $persons,
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

    public function actionDelete($id) {
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

    public function actionFullfilled($id) {
        $team = Team::findOne($id);

        if (!$team || $team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        $balance = Yii::$app->user->identity->getStock($team->teamType->product_id);
        $licences_required = count($team->members) - count($team->individualWheels);
        $licences_to_buy = $licences_required - $balance;

        if (!Yii::$app->params['monetize'] || $licences_to_buy <= 0) {
            if (Yii::$app->request->isPost) {
                foreach ($team->members as $observerMember) {
                    $wheel = Wheel::findOne([
                        'team_id' => $team->id,
                        'observer_id' => $observerMember->member->id,
                        'type' => Wheel::TYPE_INDIVIDUAL,
                    ]);

                    if (!$wheel) {
                        $token = Wheel::getNewToken();
                        $newWheel = new Wheel();

                        $newWheel->observer_id = $observerMember->member->id;
                        $newWheel->observed_id = $observerMember->member->id;
                        $newWheel->type = Wheel::TYPE_INDIVIDUAL;
                        $newWheel->token = $token;
                        $newWheel->team_id = $team->id;

                        $newWheel->save();
                    }

                    $wheel = Wheel::findOne([
                        'team_id' => $team->id,
                        'observer_id' => $observerMember->member->id,
                        'type' => Wheel::TYPE_GROUP,
                    ]);

                    if ($wheel) {
                        $token = $wheel->token;
                    } else {
                        $token = Wheel::getNewToken();
                    }

                    foreach ($team->members as $observedMember) {
                        $wheel = Wheel::findOne([
                            'team_id' => $team->id,
                            'observer_id' => $observerMember->member->id,
                            'observed_id' => $observedMember->member->id,
                            'type' => Wheel::TYPE_GROUP,
                        ]);

                        if (!$wheel) {
                            $newWheel = new Wheel();
                            $newWheel->observer_id = $observerMember->member->id;
                            $newWheel->observed_id = $observedMember->member->id;
                            $newWheel->type = Wheel::TYPE_GROUP;
                            $newWheel->token = $token;
                            $newWheel->team_id = $team->id;
                            $newWheel->save();
                        }
                    }

                    $wheel = Wheel::findOne([
                        'team_id' => $team->id,
                        'observer_id' => $observerMember->member->id,
                        'type' => Wheel::TYPE_ORGANIZATIONAL,
                    ]);

                    if ($wheel) {
                        $token = $wheel->token;
                    } else {
                        $token = Wheel::getNewToken();
                    }

                    foreach ($team->members as $observedMember) {
                        $wheel = Wheel::findOne([
                            'team_id' => $team->id,
                            'observer_id' => $observerMember->member->id,
                            'observed_id' => $observedMember->member->id,
                            'type' => Wheel::TYPE_ORGANIZATIONAL,
                        ]);

                        if (!$wheel) {
                            $newWheel = new Wheel();
                            $newWheel->observer_id = $observerMember->member->id;
                            $newWheel->observed_id = $observedMember->member->id;
                            $newWheel->type = Wheel::TYPE_ORGANIZATIONAL;
                            $newWheel->token = $token;
                            $newWheel->team_id = $team->id;
                            $newWheel->save();
                        }
                    }
                }
                SiteController::addFlash('success', Yii::t('team', 'Wheel forms has been successfully created.'));

                if (Yii::$app->params['monetize']) {
                    Stock::consume(Yii::$app->user->id, $licences_required, $team->id, $team->teamType->product_id);
                }

                Yii::$app->mailer->compose('teamFulfilled', [
                    'team' => $team,
                ])
                    ->setSubject(Yii::t('team', 'CPC: team fulfilled'))
                    ->setFrom(Yii::$app->params['senderEmail'])
                    ->setTo(User::getAdminEmails())
                    ->send();

                return $this->redirect(['/team/view', 'id' => $team->id]);
            }
        }

        return $this->render('fulfill', [
            'team' => $team,
            'balance' => $balance,
            'licences_required' => $licences_required,
            'licences_to_buy' => $licences_to_buy,
        ]);
    }

    public function actionEditMember($id) {
        $teamMember = TeamMember::findOne($id);

        $team = $teamMember->team;
        $member = $teamMember->member;

        if ($team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($member->load(Yii::$app->request->post())) {

            PersonController::upload($member, 'photo');

            if ($member->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $member->fullname]));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            }
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

    public function actionDeleteTeam($id) {
        $team = Team::findOne($id);
        $team = $team;

        if ($team->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if (Yii::$app->request->post('delete')) {
            if ($team->delete()) {
                SiteController::addFlash('success', Yii::t('team', 'Team has been successfully deleted.'));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            } else {
                SiteController::FlashErrors($team);
            }
        }

        return $this->render('delete_team', [
            'team' => $team,
            'team' => $team,
        ]);
    }

    public function actionActivateMember() {
        $id = Yii::$app->request->get("id");
        $isActive = Yii::$app->request->get("isActive");

        $teamMember = TeamMember::findOne(['id' => $id]);

        if (!$teamMember->team->isUserAllowed(Yii::$app->user->id)) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($teamMember) {
            $teamMember->active = $isActive;
            if ($teamMember->save()) {
                return 'ok';
            }
            SiteController::FlashErrors($teamMember);
        }

        return 'error';
    }

    public function actionGoToDashboard($id) {
        $team = Team::findOne(['id' => $id]);
        $filter = new DashboardFilter();

        $filter->companyId = $team->company_id;
        $filter->teamId = $team->id;
        $filter->teamId = $id;
        $filter->wheelType = Wheel::TYPE_GROUP;

        Yii::$app->session->set('DashboardFilter', $filter);
        $this->redirect(['/dashboard']);
    }

    public function actionGrantCoach($id) {
        $team = Team::findOne(['id' => $id]);
        $coach_id = Yii::$app->request->post('coach_id');

        //if ($team->coach_id == Yii::$app->user->identity->id && $coach_id) {
        if ($coach_id) {
            if (TeamCoach::notGranted($id, $coach_id)) {
                $model = new TeamCoach([
                    'team_id' => $id,
                    'coach_id' => $coach_id,
                    'anonimize' => true,
                ]);
                if ($model->save()) {
                    SiteController::addFlash('success', Yii::t('team', 'Access granted'));
                }
            }
        }

        return $this->redirect(['/team/view', 'id' => $id]);
    }

    public function actionRemoveCoach($id) {
        $teamCoach = TeamCoach::findOne(['id' => $id]);
        $teamCoach->delete();

        SiteController::addFlash('success', Yii::t('team', 'Access removed'));

        return $this->redirect(['/team/view', 'id' => $teamCoach->team_id]);
    }

    public function actionSendWheel($id, $memberId, $type) {
        $team = Team::findOne(['id' => $id]);

        $sent = false;
        foreach ($team->members as $teamMember) {
            if ($teamMember->person_id == $memberId) {
                $wheels = [];
                switch ($type) {
                    case Wheel::TYPE_INDIVIDUAL:
                        $wheels = $team->individualWheels;
                        break;
                    case Wheel::TYPE_GROUP:
                        $wheels = $team->groupWheels;
                        break;
                    default:
                        $wheels = $team->organizationalWheels;
                        break;
                }

                foreach ($wheels as $wheel) {
                    if ($wheel->observer_id == $memberId && $wheel->answerStatus != '100%') {
                        $this->sendWheel($wheel);
                        $sent = true;
                        break;
                    }
                }
            }
        }

        if ($sent == false) {
            \Yii::$app->session->addFlash('info', \Yii::t('team', 'Wheel already fullfilled. Email not sent.'));
        }
        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    public function actionSendAllWheel($id, $type) {
        $team = Team::findOne(['id' => $id]);

        $sent = false;
        foreach ($team->members as $teamMember) {
            $wheels = [];
            switch ($type) {
                case Wheel::TYPE_INDIVIDUAL:
                    $wheels = $team->individualWheels;
                    break;
                case Wheel::TYPE_GROUP:
                    $wheels = $team->groupWheels;
                    break;
                default:
                    $wheels = $team->organizationalWheels;
                    break;
            }

            foreach ($wheels as $wheel) {
                if ($wheel->observer_id == $teamMember->person_id && $wheel->answerStatus != '100%') {
                    $this->sendWheel($wheel);
                    $sent = true;
                    break;
                }
            }
        }

        if ($sent == false) {
            \Yii::$app->session->addFlash('info', \Yii::t('team', 'All wheels already fullfilled. Emails not sent.'));
        }
        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    private function getCompanies() {
        return $companies = ArrayHelper::map(Company::browse()->asArray()->all(), 'id', 'name');
    }

    private function getPersons() {
        $persons = [];
        foreach (Person::browse()->all() as $person) {
            $persons[$person->id] = $person->fullname;
        }
        return $persons;
    }

    private function sendWheel($wheel) {
        $wheel_type = Wheel::getWheelTypes()[$wheel->type];
        $sent = Yii::$app->mailer->compose('wheel', [
            'wheel' => $wheel,
        ])
            ->setSubject(Yii::t('team', 'CPC: access to {wheel_type} of team {team}', [
                'wheel_type' => $wheel_type,
                'team' => $wheel->team->name,
            ]))
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo($wheel->observer->email)
            ->setReplyTo($wheel->coach->email)
            ->send();

        if ($sent) {
            SiteController::addFlash('success', \Yii::t('team', '{wheel_type} sent to {user}.', ['wheel_type' => $wheel_type, 'user' => $wheel->observer->fullname]));
            $wheels = Wheel::find()->where(['token' => $wheel->token])->all();
            foreach ($wheels as $wheel) {
                if ($wheel->status == 'created') {
                    $wheel->status = 'sent';
                    $wheel->save();
                }
            }
        } else {
            SiteController::addFlash('error', \Yii::t('team', '{wheel_type} not sent to {user}.', ['wheel_type' => $wheel_type, 'user' => $wheel->observer->fullname]));
        }
    }

}
