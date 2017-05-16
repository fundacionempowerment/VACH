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

class ReportController extends Controller
{

    const ANALYSIS_OPTIONS = [
        'height' => '500px',
        'toolbarGroups' => [
            ['name' => 'undo'],
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
            ['name' => 'paragraph', 'groups' => ['list']],
        ],
        'removeButtons' => 'Underline,Strike,Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe',
    ];
    const SUMMARY_OPTIONS = [
        'height' => '250px',
        'toolbarGroups' => [
            ['name' => 'undo'],
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
            ['name' => 'paragraph', 'groups' => ['list']],
        ],
        'removeButtons' => 'Underline,Strike,Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe',
    ];

    public $layout = 'inner';

    private function sanitize($string)
    {
        $string = strip_tags($string, '<b><i><p><br><strong><em><ul><li><ol>');
        $string = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $string);
        $string = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $string);
        $string = preg_replace('/(<[^>]+) align=".*?"/i', '$1', $string);
        $string = str_replace('<br>', '<br/>', $string);
        $string = \yii\helpers\HtmlPurifier::process($string);
        return $string;
    }

    public function actionView($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if ($assessment->report == null) {
            $newReport = new Report();
            $assessment->link('report', $newReport);
        }

        foreach ($assessment->team->members as $teamMember) {
            $exists = false;
            if (count($assessment->report->individualReports) > 0) {
                foreach ($assessment->report->individualReports as $individualReport)
                    if ($individualReport->person_id == $teamMember->person_id) {
                        $exists = true;
                        break;
                    }
            }

            if (!$exists) {
                $newIndividualReport = new IndividualReport();
                $newIndividualReport->person_id = $teamMember->person_id;
                $assessment->report->link('individualReports', $newIndividualReport);
            }
        }

        return $this->render('view', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionIntroduction($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $assessment->report->introduction = $analysis;
            $assessment->report->introduction_keywords = $keywords;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'introduction']);
        }

        return $this->render('introduction', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionEffectiveness($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $assessment->report->effectiveness = $analysis;
            $assessment->report->effectiveness_keywords = $keywords;

            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'effectiveness']);
        }

        $members = [];

        $groupRelationsMatrix = [];
        $organizationalRelationsMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('effectiveness', [
                    'assessment' => $assessment,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'members' => $members,
                    'member' => null,
        ]);
    }

    public function actionPerformance($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $assessment->report->performance = $analysis;
            $assessment->report->performance_keywords = $keywords;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'performance']);
        }

        $members = [];

        $groupPerformanceMatrix = [];
        $organizationalPerformanceMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

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

    public function actionRelations($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $assessment->report->relations = $analysis;
            $assessment->report->relations_keywords = $keywords;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'relations']);
        }

        $members = [];

        $groupRelationsMatrix = [];
        $organizationalRelationsMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

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

    public function actionCompetences($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $assessment->report->competences = $analysis;
            $assessment->report->competences_keywords = $keywords;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'competences']);
        }

        $members = [];
        $groupGauges = [];
        $organizationalGauges = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

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

    public function actionEmergents($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $assessment->report->emergents = $analysis;
            $assessment->report->emergents_keywords = $keywords;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'emergents']);
        }

        $members = [];
        $groupEmergents = [];
        $organizationalEmergents = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupEmergents = Wheel::getEmergents($assessment->id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getEmergents($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('emergents', [
                    'assessment' => $assessment,
                    'groupEmergents' => $groupEmergents,
                    'organizationalEmergents' => $organizationalEmergents,
                    'members' => $members,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'memberId' => 0,
        ]);
    }

    public function actionActionPlan($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);

            $assessment->report->action_plan = $analysis;
            $assessment->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'action-plan']);
        }

        return $this->render('action_plan', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionIndividualPerformance($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->performance = $analysis;
            $individualReport->performance_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'performance-' . $individualReport->id]);
        }

        $members = [];

        $groupPerformanceMatrix = [];
        $organizationalPerformanceMatrix = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalPerformanceMatrix = Wheel::getPerformanceMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_performance', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupPerformanceMatrix' => $groupPerformanceMatrix,
                    'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                    'members' => $members,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
        ]);
    }

    public function actionIndividualPerception($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->perception = $analysis;
            $individualReport->perception_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'perception-' . $individualReport->id]);
        }

        $projectedGroupWheel = Wheel::getProjectedGroupWheel($assessment->id, $individualReport->person_id);
        $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($assessment->id, $individualReport->person_id);
        $reflectedGroupWheel = Wheel::getReflectedGroupWheel($assessment->id, $individualReport->person_id);
        $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($assessment->id, $individualReport->person_id);

        return $this->render('individual_perception', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                    'reflectedGroupWheel' => $reflectedGroupWheel,
                    'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
        ]);
    }

    public function actionIndividualRelations($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->relations = $analysis;
            $individualReport->relations_keywords = $keywords;
            $individualReport->relations = $analysis;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'relations-' . $individualReport->id]);
        }

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

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

    public function actionIndividualCompetences($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->competences = $analysis;
            $individualReport->competences_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'individual-competences-' . $individualReport->id]);
        }

        $members = [];
        $groupGauges = [];
        $organizationalGauges = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupGauges = Wheel::getMemberGauges($assessment->id, $individualReport->person_id, Wheel::TYPE_GROUP);
        $organizationalGauges = Wheel::getMemberGauges($assessment->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_competences', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupGauges' => $groupGauges,
                    'organizationalGauges' => $organizationalGauges,
                    'members' => $members,
        ]);
    }

    public function actionIndividualEmergents($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $assessment = $individualReport->report->assessment;
        $this->checkAllowed($assessment);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->emergents = $analysis;
            $individualReport->emergents_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $assessment->id, '#' => 'individual-emergents-' . $individualReport->id]);
        }

        $members = [];
        $groupEmergents = [];
        $organizationalEmergents = [];

        foreach (TeamMember::find()->where(['team_id' => $assessment->team->id])->all() as $teamMember)
            $members[$teamMember->person_id] = $teamMember->member->fullname;

        $members[0] = Yii::t('app', 'All');

        $groupEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->person_id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getMemberEmergents($assessment->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);

        $groupRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($assessment->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_emergents', [
                    'report' => $individualReport,
                    'assessment' => $assessment,
                    'groupEmergents' => $groupEmergents,
                    'organizationalEmergents' => $organizationalEmergents,
                    'members' => $members,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'memberId' => $individualReport->person_id,
        ]);
    }

    public function actionDownload($id)
    {
        $this->layout = 'printable';

        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        $members = [];

        $groupRelationsMatrix = [];
        $organizationalRelationsMatrix = [];

        foreach (TeamMember::find()->where([
            'team_id' => $assessment->team->id,
            'active' => true,
        ])->all() as $teamMember) {
            $members[$teamMember->person_id] = $teamMember->member->fullname;
        }

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

    public function actionPresentation($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        $ppt = \app\components\Presentation::create($assessment);

        $oWriterPPTX = \PhpOffice\PhpPresentation\IOFactory::createWriter($ppt, 'PowerPoint2007');

        $uuid = uniqid('', true);
        $oWriterPPTX->save("/tmp/$uuid.pptx");

        return \Yii::$app->response->sendFile("/tmp/$uuid.pptx", $assessment->fullname . '.' . date('Y-m-d') . '.pptx');
    }

    public function actionWord($id)
    {
        $assessment = Assessment::findOne(['id' => $id]);
        $this->checkAllowed($assessment);

        $phpWord = \app\components\Word::create($assessment);

        $uuid = uniqid('', true);
        $phpWord->save("/tmp/$uuid.docx", 'Word2007');

        return \Yii::$app->response->sendFile("/tmp/$uuid.docx", $assessment->fullname . '.' . date('Y-m-d') . '.docx');
    }

    private function checkAllowed($assessment)
    {
        if (!$assessment->isUserAllowed()) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }
    }

}
