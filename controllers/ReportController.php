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

class ReportController extends Controller {

    public $layout = 'inner';

    public function actionTechnical($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        if ($assessment->report == null) {
            $newReport = new Report();
            $newReport->assessment_id = $id;
            $newReport->save();
        }

        foreach ($assessment->team->members as $teamMember) {
            $exists = false;
            foreach ($assessment->report->individuals as $individual)
                if ($individual->user_id == $teamMember->user_id) {
                    $exists = true;
                    break;
                }

            if (!$exists) {
                $newIndividualReport = new IndividualReport();
                $newIndividualReport->user_id = $teamMember->user_id;
                $newIndividualReport->report_id = $assessment->report->id;
                $newIndividualReport->save();
            }
        }

        return $this->render('technical', [
                    'assessment' => $assessment,
        ]);
    }

}

