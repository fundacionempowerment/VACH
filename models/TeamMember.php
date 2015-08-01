<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class TeamMember extends ActiveRecord {

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['user_id', 'team_id'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'user_id' => Yii::t('team', 'Member'),
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

    public function getMember() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getTeam() {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

}
