<?php

namespace app\controllers;

use app\components\ReportHelper;
use app\models\ClientModel;
use app\models\IndividualReport;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\Team;
use app\models\Wheel;
use Yii;
use yii\web\Controller;

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
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        ReportHelper::populate($team);
        $team->refresh();

        foreach ($team->members as $teamMember) {
            $exists = false;
            if (count($team->report->individualReports) > 0) {
                foreach ($team->report->individualReports as $individualReport) {
                    if ($individualReport->person_id == $teamMember->person_id) {
                        $exists = true;
                        break;
                    }
                }
            }

            if (!$exists) {
                $newIndividualReport = new IndividualReport();
                $newIndividualReport->person_id = $teamMember->person_id;
                $team->report->link('individualReports', $newIndividualReport);
            }
        }

        return $this->render('view', [
            'team' => $team,
        ]);
    }

    public function actionIntroduction($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $team->report->introduction = $analysis;
            $team->report->introduction_keywords = $keywords;
            $team->report->save();
            $team->report->introduction = $analysis;
            $team->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'introduction']);
        }

        return $this->render('introduction', [
            'team' => $team,
        ]);
    }

    public function actionEffectiveness($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $team->report->effectiveness = $analysis;
            $team->report->effectiveness_keywords = $keywords;

            $team->report->save();
            $team->report->effectiveness = $analysis;
            $team->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'effectiveness']);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('effectiveness', [
            'team' => $team,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
            'member' => null,
        ]);
    }

    public function actionPerformance($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $team->report->performance = $analysis;
            $team->report->performance_keywords = $keywords;
            $team->report->save();
            $team->report->performance = $analysis;
            $team->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'performance']);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupPerformanceMatrix = Wheel::getProdConsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalPerformanceMatrix = Wheel::getProdConsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('performance', [
            'team' => $team,
            'groupPerformanceMatrix' => $groupPerformanceMatrix,
            'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
            'members' => $members,
        ]);
    }

    public function actionRelations($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $team->report->relations = $analysis;
            $team->report->relations_keywords = $keywords;
            $team->report->save();
            $team->report->relations = $analysis;
            $team->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'relations']);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('relations', [
            'team' => $team,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
        ]);
    }

    public function actionCompetences($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $team->report->competences = $analysis;
            $team->report->competences_keywords = $keywords;
            $team->report->save();
            $team->report->competences = $analysis;
            $team->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'competences']);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupCompetences = Wheel::getPerceptions($team->id, Wheel::TYPE_GROUP);
        $organizationalCompetences = Wheel::getPerceptions($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('competences', [
            'team' => $team,
            'groupCompetences' => $groupCompetences,
            'organizationalCompetences' => $organizationalCompetences,
            'members' => $members,
        ]);
    }

    public function actionEmergents($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $team->report->emergents = $analysis;
            $team->report->emergents_keywords = $keywords;
            $team->report->save();
            $team->report->emergents = $analysis;
            $team->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'emergents']);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupEmergents = Wheel::getEmergents($team->id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getEmergents($team->id, Wheel::TYPE_ORGANIZATIONAL);

        $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('emergents', [
            'team' => $team,
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
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);

            $team->report->action_plan = $analysis;
            $team->report->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $id, '#' => 'action-plan']);
        }

        return $this->render('action_plan', [
            'team' => $team,
        ]);
    }

    public function actionIndividualPerformance($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $team = $individualReport->report->team;
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->performance = $analysis;
            $individualReport->performance_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $team->id, '#' => 'performance-' . $individualReport->id]);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupPerformanceMatrix = Wheel::getProdConsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalPerformanceMatrix = Wheel::getProdConsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_performance', [
            'report' => $individualReport,
            'team' => $team,
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

        $team = $individualReport->report->team;
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->perception = $analysis;
            $individualReport->perception_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $team->id, '#' => 'perception-' . $individualReport->id]);
        }

        $projectedGroupWheel = Wheel::getProjectedGroupWheel($team->id, $individualReport->person_id);
        $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($team->id, $individualReport->person_id);
        $reflectedGroupWheel = Wheel::getReflectedGroupWheel($team->id, $individualReport->person_id);
        $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($team->id, $individualReport->person_id);

        return $this->render('individual_perception', [
            'report' => $individualReport,
            'team' => $team,
            'projectedGroupWheel' => $projectedGroupWheel,
            'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
            'reflectedGroupWheel' => $reflectedGroupWheel,
            'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
        ]);
    }

    public function actionIndividualRelations($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $team = $individualReport->report->team;
        $this->checkAllowed($team);

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
            return $this->redirect(['/report/view', 'id' => $team->id, '#' => 'relations-' . $individualReport->id]);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_relations', [
            'report' => $individualReport,
            'team' => $team,
            'members' => $members,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
        ]);
    }

    public function actionIndividualCompetences($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $team = $individualReport->report->team;
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->competences = $analysis;
            $individualReport->competences_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $team->id, '#' => 'individual-competences-' . $individualReport->id]);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupCompetences = Wheel::getMemberCompetences($team->id, $individualReport->person_id, Wheel::TYPE_GROUP);
        $organizationalCompetences = Wheel::getMemberCompetences($team->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_competences', [
            'report' => $individualReport,
            'team' => $team,
            'groupCompetences' => $groupCompetences,
            'organizationalCompetences' => $organizationalCompetences,
            'members' => $members,
        ]);
    }

    public function actionIndividualEmergents($id)
    {
        $individualReport = IndividualReport::findOne(['id' => $id]);

        $team = $individualReport->report->team;
        $this->checkAllowed($team);

        if (Yii::$app->request->isPost) {
            $analysis = Yii::$app->request->post('analysis');
            $analysis = $this->sanitize($analysis);
            $keywords = Yii::$app->request->post('keywords');
            $keywords = $this->sanitize($keywords);

            $individualReport->emergents = $analysis;
            $individualReport->emergents_keywords = $keywords;
            $individualReport->save();
            \Yii::$app->session->addFlash('success', \Yii::t('report', 'Analysis saved.'));
            return $this->redirect(['/report/view', 'id' => $team->id, '#' => 'individual-emergents-' . $individualReport->id]);
        }

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupEmergents = Wheel::getMemberEmergents($team->id, $individualReport->person_id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getMemberEmergents($team->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);

        $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('individual_emergents', [
            'report' => $individualReport,
            'team' => $team,
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

        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        $members = $team->activeMemberList;

        $members[0] = Yii::t('app', 'All');

        $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);
        $groupPerformanceMatrix = Wheel::getProdConsMatrix($team->id, Wheel::TYPE_GROUP);
        $organizationalPerformanceMatrix = Wheel::getProdConsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);
        $groupCompetences = Wheel::getPerceptions($team->id, Wheel::TYPE_GROUP);
        $organizationalCompetences = Wheel::getPerceptions($team->id, Wheel::TYPE_ORGANIZATIONAL);

        $groupEmergents = Wheel::getEmergents($team->id, Wheel::TYPE_GROUP);
        $organizationalEmergents = Wheel::getEmergents($team->id, Wheel::TYPE_ORGANIZATIONAL);

        return $this->render('download', [
            'team' => $team,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'groupPerformanceMatrix' => $groupPerformanceMatrix,
            'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
            'groupCompetences' => $groupCompetences,
            'organizationalCompetences' => $organizationalCompetences,
            'groupEmergents' => $groupEmergents,
            'organizationalEmergents' => $organizationalEmergents,
            'members' => $members,
        ]);
    }

    public function actionPresentation($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        $ppt = \app\components\Presentation::create($team);

        $oWriterPPTX = \PhpOffice\PhpPresentation\IOFactory::createWriter($ppt, 'PowerPoint2007');

        $uuid = uniqid('', true);
        $oWriterPPTX->save("/tmp/$uuid.pptx");

        if (isset(\app\components\Presentation::$paths)) {
            foreach (\app\components\Presentation::$paths as $path) {
                unlink($path);
            }
        }

        return \Yii::$app->response->sendFile("/tmp/$uuid.pptx", $team->fullname . '.' . date('Y-m-d') . '.pptx');
    }

    public function actionWord($id)
    {
        $team = Team::findOne(['id' => $id]);
        $this->checkAllowed($team);

        $phpWord = \app\components\Word::create($team);

        $uuid = uniqid('', true);
        $phpWord->save("/tmp/$uuid.docx", 'Word2007');

        foreach (\app\components\Word::$paths as $path) {
            unlink($path);
        }

        return \Yii::$app->response->sendFile("/tmp/$uuid.docx", $team->fullname . '.' . date('Y-m-d') . '.docx');
    }

    private function checkAllowed($team)
    {
        if (!$team->isUserAllowed()) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }
    }

}
