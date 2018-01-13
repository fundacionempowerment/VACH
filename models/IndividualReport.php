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

}
