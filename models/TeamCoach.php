<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class TeamCoach extends ActiveRecord
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
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

    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    public function getCoach()
    {
        return $this->hasOne(User::class, ['id' => 'coach_id']);
    }

    static public function notGranted($teamId, $coachId)
    {
        return !self::find()
                        ->where([
                            'team_id' => $teamId,
                            'coach_id' => $coachId,
                        ])->exists();
    }

}
