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
use kartik\mpdf\Pdf;

class ReportController extends Controller {

    public $layout = 'inner';

    public function actionView($id) {
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

    public function actionIntroduction($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->introduction = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'introduction']);
        }

        return $this->render('introduction', [
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
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'effectiveness']);
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
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'performance']);
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

    public function actionRelations($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->relations = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'relations']);
        }

        $members = [];

        $groupRelationsMatrix = [];
        $organizationalRelationsMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('relations', [
                    'assessment' => $assessment,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
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
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'competences']);
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
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'emergents']);
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

    public function actionIndividualPerformance($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->performance = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'performance-' . $individualReport->id]);
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

    public function actionIndividualPerception($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->perception = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'perception-' . $individualReport->id]);
        }

        $projectedGroupWheel = Wheel::getProjectedGroupWheel($assessment->id, $individualReport->user_id);
        $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($assessment->id, $individualReport->user_id);
        $reflectedGroupWheel = Wheel::getReflectedGroupWheel($assessment->id, $individualReport->user_id);
        $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($assessment->id, $individualReport->user_id);

        return $this->render('individual_perception', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                    'reflectedGroupWheel' => $reflectedGroupWheel,
                    'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
        ]);
    }

    public function actionIndividualRelations($id) {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $individualReport->relations = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'relations-' . $individualReport->id]);
        }

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_relations', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'members' => $members,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
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
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'individual-competences-' . $individualReport->id]);
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
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'individual-emergents-' . $individualReport->id]);
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

    public function actionSummary($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->summary = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'summary']);
        }

        return $this->render('summary', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionActionPlan($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = strip_tags($analysis, '<b><i><p><ul><li><ol><br>');
            $assessment->report->action_plan = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'action-plan']);
        }

        return $this->render('action_plan', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionDownload($id) {
        $this->layout = 'printable';

        $assessment = Assessment::findOne(['id' => $id]);

        $members = [];

        $groupRelationsMatrix = [];
        $organizationalRelationsMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->user_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);
        $groupPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);
        $groupGauges = Wheel::getGauges($assessment->id, Wheel::TYPE_GROUP);
        $organizationalGauges = Wheel::getGauges($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        $groupEmergents = Wheel::getEmergents($assessment->id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getEmergents($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('download', [
                    'assessment' => $assessment,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'groupPerformanceMatrix' => $groupPerformanceMatrix,
                    'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                    'groupGauges' => $groupGauges,
                    'organizationalGauges' => $organizationalGauges,
                    'groupEmergents' => $groupEmergents,
                    'organizationalEmergents' => $organizationalEmergents,
                    'members' => $members,
        ]);
    }

}
