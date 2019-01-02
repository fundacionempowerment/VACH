<?php

namespace app\components;


use app\models\Report;
use app\models\Team;
use yii\db\Exception;
use yii\web\View;

class ReportHelper {
    static public function populate(Team $team) {
        $report = Report::findOne(['team_id' => $team->id]);
        if (!$report) {
            $report = new Report();
            $report->team_id = $team->id;

            if (!$report->save()) {
                throw new Exception("Report no saved");
            }
        }

        //self::fillIntroduction($report);
        //self::fillRelations($report);

        $report->refresh();
        return $report;
    }

    static public function fillIntroduction(Report $report) {
        if ($report->introduction) {
            return;
        }

        $view = new View();
        $text = $view->render('@app/templates/report/introduction', ['team' => $report->team]);
        $report->introduction = $text;
    }

    private static function fillRelations(Report $report) {
        if ($report->relations) {
            return;
        }

        $view = new View();
        $text = $view->render('@app/templates/report/relations', [
            'team' => $report->team
        ]);
        $report->relations = $text;
    }
}