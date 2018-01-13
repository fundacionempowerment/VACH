<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Report extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['team_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getIndividualReports()
    {
        return $this->hasMany(IndividualReport::className(), ['report_id' => 'id']);
    }

    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    public function getSummary()
    {
        $summary = '';

        $summary .= $this->introduction_keywords;
        $summary .= $this->effectiveness_keywords;
        $summary .= $this->performance_keywords;
        $summary .= $this->relations_keywords;
        $summary .= $this->emergents_keywords;
        $summary .= $this->competences_keywords;

        foreach ($this->individualReports as $report) {
            $summary .= $report->performance_keywords;
            $summary .= $report->perception_keywords;
            $summary .= $report->relations_keywords;
            $summary .= $report->competences_keywords;
            $summary .= $report->emergents_keywords;
        }

        return $summary;
    }

}
