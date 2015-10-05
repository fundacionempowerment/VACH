<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\CoachModel;
use app\models\ClientModel;
use app\models\Assessment;
use app\models\Report;
use app\models\IndividualReport;
use app\models\Wheel;
use app\models\TeamMember;

class ReportController extends Controller {

    public $layout = 'inner';

    public function actionTechnical($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if ($assessment->report == null) {
            $newReport = new Report();
            $assessment->link('report', $newReport);
        }

        foreach ($assessment->team->members as $teamMember) {
            $exists = false;
            if (count($assessment->report->individualReports) > 0) {
                foreach ($assessment->report->individualReports as $individualReport)
                    if ($individualReport->user_id == $teamMember->user_id) {
                        $exists = true;
                        break;
                    }
            }

            if (!$exists) {
                $newIndividualReport = new IndividualReport();
                $newIndividualReport->user_id = $teamMember->user_id;
                $assessment->report->link('individualReports', $newIndividualReport);
            }
        }

        return $this->render('view', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionEffectiveness($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->effectiveness = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $id]);
        }

        $members = [];

        $groupRelationsMatrix = [];
        $organizationalRelationsMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('effectiveness', [
                    'assessment' => $assessment,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'members' => $members,
        ]);
    }

    public function actionPerformance($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->performance = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $id]);
        }

        $members = [];

        $groupPerformanceMatrix = [];
        $organizationalPerformanceMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('performance', [
                    'assessment' => $assessment,
                    'groupPerformanceMatrix' => $groupPerformanceMatrix,
                    'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                    'members' => $members,
        ]);
    }

    public function actionCompetences($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->competences = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $id]);
        }

        $members = [];
        $groupGauges = [];
        $organizationalGauges = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupGauges = Wheel::getGauges($assessment->id, Wheel::TYPE_GROUP);
        $organizationalGauges = Wheel::getGauges($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('competences', [
                    'assessment' => $assessment,
                    'groupGauges' => $groupGauges,
                    'organizationalGauges' => $organizationalGauges,
                    'members' => $members,
        ]);
    }

    public function actionEmergents($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->emergents = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $id]);
        }

        $members = [];
        $groupEmergents = [];
        $organizationalEmergents = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupEmergents = Wheel::getEmergents($assessment->id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getEmergents($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('emergents', [
                    'assessment' => $assessment,
                    'groupEmergents' => $groupEmergents,
                    'organizationalEmergents' => $organizationalEmergents,
                    'members' => $members,
        ]);
    }

    public function actionPerception($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->perception = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $assessment->id]);
        }

        $projectedGroupWheel = Wheel::getProjectedGroupWheel($assessment->id, $individualReport->user_id);
        $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($assessment->id, $individualReport->user_id);
        $reflectedGroupWheel = Wheel::getReflectedGroupWheel($assessment->id, $individualReport->user_id);
        $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($assessment->id, $individualReport->user_id);

        return $this->render('perception', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                    'reflectedGroupWheel' => $reflectedGroupWheel,
                    'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
        ]);
    }

    public function actionRelations($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->relations = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $assessment->id]);
        }

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('relations', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'members' => $members,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
        ]);
    }

    public function actionIndividualPerformance($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->performance = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $assessment->id]);
        }

        $members = [];

        $groupPerformanceMatrix = [];
        $organizationalPerformanceMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_performance', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupPerformanceMatrix' => $groupPerformanceMatrix,
                    'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                    'members' => $members,
        ]);
    }

    public function actionIndividualCompetences($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->competences = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $assessment->id]);
        }

        $members = [];
        $groupGauges = [];
        $organizationalGauges = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupGauges = Wheel::getMemberGauges($assessment->id, $individualReport->user_id, Wheel::TYPE_GROUP);
        $organizationalGauges = Wheel::getMemberGauges($assessment->id, $individualReport->user_id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_competences', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupGauges' => $groupGauges,
                    'organizationalGauges' => $organizationalGauges,
                    'members' => $members,
        ]);
    }

    public function actionIndividualEmergents($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->emergents = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/technical', 'id' => $assessment->id]);
        }

        $members = [];
        $groupEmergents = [];
        $organizationalEmergents = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->user_id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->user_id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_emergents', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupEmergents' => $groupEmergents,
                    'organizationalEmergents' => $organizationalEmergents,
                    'members' => $members,
        ]);
    }

}

