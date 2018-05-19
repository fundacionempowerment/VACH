<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class IndividualReport extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['report_id', 'person_id'], 'required'],
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

    public function getMember()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    public function getTeamMember()
    {
        return TeamMember::findOne([
                    'person_id' => $this->person_id,
                    'team_id' => $this->report->team_id,
        ]);
    }

    public function getReport()
    {
        return $this->hasOne(Report::className(), ['id' => 'report_id']);
    }

    public function getPerception()
    {
        return $this->getContent($this->perception_id);
    }

    public function getPerception_keywords()
    {
        return $this->getContent($this->perception_keywords_id);
    }

    public function setPerception($content)
    {
        return $this->setContent('perception_id', $this->perception_id, $content);
    }

    public function setPerception_keywords($content)
    {
        return $this->setContent('perception_keywords_id', $this->perception_keywords_id, $content);
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

            Yii::$app->db->createCommand()->update('individual_report', [$field => $id,], ['id' => $this->id]
            )->execute();
        }
    }

}
