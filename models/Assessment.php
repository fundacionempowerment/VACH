<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Assessment extends ActiveRecord {

    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_FINISHED = 2;

    public $name;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['team_id'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'team_id' => Yii::t('team', 'Team'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function afterFind() {
        $this->name = '#' . $this->id . ' - ' . $this->team->fullname . ' ';
    }

    public function getTeam() {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    public function wheelStatus($type) {
        return (new Query)->select('wheel.observer_id, wheel.token, user.name, user.surname, count(wheel_answer.id) as count')
                        ->from('wheel')
                        ->leftJoin('wheel_answer', 'wheel_answer.wheel_id = wheel.id')
                        ->where(['assessment_id' => $this->id, 'type' => $type])
                        ->innerJoin('user', 'user.id = wheel.observer_id')
                        ->groupBy('wheel.observer_id, wheel.token, user.name, user.surname')
                        ->all();
    }

    public function getIndividualWheels() {
        return $this->hasMany(Wheel::className(), ['assessment_id' => 'id'])->where(['type' => Wheel::TYPE_INDIVIDUAL]);
    }

    public function getGroupWheels() {
        return $this->hasMany(Wheel::className(), ['assessment_id' => 'id'])->where(['type' => Wheel::TYPE_GROUP]);
    }

    public function getOrganizationalWheels() {
        return $this->hasMany(Wheel::className(), ['assessment_id' => 'id'])->where(['type' => Wheel::TYPE_ORGANIZATIONAL]);
    }

}
