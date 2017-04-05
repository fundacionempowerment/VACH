<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class AssessmentCoach extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    public function getAssessment()
    {
        return $this->hasOne(Assessment::className(), ['id' => 'assessment_id']);
    }

    public function getCoach()
    {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    static public function notGranted($assessmentId, $coachId)
    {
        return !self::find()
                        ->where([
                            'assessment_id' => $assessmentId,
                            'coach_id' => $coachId,
                        ])->exists();
    }

}
