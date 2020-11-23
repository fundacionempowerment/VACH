<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Report
 * @package app\models
 * @property integer team_id
 * @property integer introduction_id
 * @property string introduction
 * @property integer relations_id
 * @property string relations
 * @property integer created_at
 * @property integer updated_at
 *
 * @property Team $team
 */
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
            TimestampBehavior::class,
        ];
    }

    public function getIndividualReports()
    {
        return $this->hasMany(IndividualReport::class, ['report_id' => 'id']);
    }

    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    public function getSummary()
    {
        $summary = '';

        $summary .= $this->introduction_keywords;
        $summary .= $this->relations_keywords;
        $summary .= $this->effectiveness_keywords;
        $summary .= $this->performance_keywords;
        $summary .= $this->competences_keywords;
        $summary .= $this->emergents_keywords;

        foreach ($this->individualReports as $report) {
            $summary .= $report->perception_keywords;
            $summary .= $report->competences_keywords;
            $summary .= $report->emergents_keywords;
            $summary .= $report->relations_keywords;
            $summary .= $report->performance_keywords;
        }

        return $summary;
    }

    public function getIntroduction()
    {
        return $this->getContent($this->introduction_id);
    }

    public function getIntroduction_keywords()
    {
        return $this->getContent($this->introduction_keywords_id);
    }

    public function setIntroduction($content)
    {
        return $this->setContent('introduction_id', $this->introduction_id, $content);
    }

    public function setIntroduction_keywords($content)
    {
        return $this->setContent('introduction_keywords_id', $this->introduction_keywords_id, $content);
    }

    public function getRelations()
    {
        return $this->getContent($this->relations_id);
    }

    public function getRelations_keywords()
    {
        return $this->getContent($this->relations_keywords_id);
    }

    public function setRelations($content)
    {
        return $this->setContent('relations_id', $this->relations_id, $content);
    }

    public function setRelations_keywords($content)
    {
        return $this->setContent('relations_keywords_id', $this->relations_keywords_id, $content);
    }

    public function getEffectiveness()
    {
        return $this->getContent($this->effectiveness_id);
    }

    public function getEffectiveness_keywords()
    {
        return $this->getContent($this->effectiveness_keywords_id);
    }

    public function setEffectiveness($content)
    {
        return $this->setContent('effectiveness_id', $this->effectiveness_id, $content);
    }

    public function setEffectiveness_keywords($content)
    {
        return $this->setContent('effectiveness_keywords_id', $this->effectiveness_keywords_id, $content);
    }

    public function getPerformance()
    {
        return $this->getContent($this->performance_id);
    }

    public function getPerformance_keywords()
    {
        return $this->getContent($this->performance_keywords_id);
    }

    public function setPerformance($content)
    {
        return $this->setContent('performance_id', $this->performance_id, $content);
    }

    public function setPerformance_keywords($content)
    {
        return $this->setContent('performance_keywords_id', $this->performance_keywords_id, $content);
    }

    public function getCompetences()
    {
        return $this->getContent($this->competences_id);
    }

    public function getCompetences_keywords()
    {
        return $this->getContent($this->competences_keywords_id);
    }

    public function setCompetences($content)
    {
        return $this->setContent('competences_id', $this->competences_id, $content);
    }

    public function setCompetences_keywords($content)
    {
        return $this->setContent('competences_keywords_id', $this->competences_keywords_id, $content);
    }

    public function getEmergents()
    {
        return $this->getContent($this->emergents_id);
    }

    public function getEmergents_keywords()
    {
        return $this->getContent($this->emergents_keywords_id);
    }

    public function setEmergents($content)
    {
        return $this->setContent('emergents_id', $this->emergents_id, $content);
    }

    public function setEmergents_keywords($content)
    {
        return $this->setContent('emergents_keywords_id', $this->emergents_keywords_id, $content);
    }

    public function getAction_plan()
    {
        return $this->getContent($this->action_plan_id);
    }

    public function setAction_plan($content)
    {
        return $this->setContent('action_plan_id', $this->action_plan_id, $content);
    }

    ///////////////////////

    private function getContent($id)
    {
        if ($id) {
            $content = $this->db->createCommand("SELECT `content` FROM `report_field` where `report_field_id` = $id")->queryScalar();
            return $content;
        }
        return '';
    }

    private function setContent($field, $id, $content)
    {
        if ($id) {
            Yii::$app->db->createCommand()->update('report_field', ['content' => $content], ['report_field_id' => $id])->execute();
        } else {
            Yii::$app->db->createCommand()->insert('report_field', [
                'content' => $content,
            ])->execute();

            $id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand()->update('report', [$field => $id,], ['id' => $this->id]
            )->execute();
        }
    }

    public function canAutofill() {
        return in_array(Yii::$app->user->id, Yii::$app->params['report_autofill_users']) || YII_ENV_TEST;
    }

}
