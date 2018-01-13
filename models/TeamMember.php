<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class TeamMember extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['person_id', 'team_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'person_id' => Yii::t('team', 'Member'),
            'team_id' => Yii::t('team', 'Team'),
            'active' => Yii::t('app', 'Active'),
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

    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    public function getDeletable()
    {
        $own = $this->team->coach_id == Yii::$app->user->identity->id;
        $wheel = Wheel::find()
                ->where([
                    'observer_id' => $this->person_id,
                    'team_id' => $this->team->id,
                    'type' => Wheel::TYPE_INDIVIDUAL,
                ])
                ->exists();
        return $own && !$wheel;
    }

}
