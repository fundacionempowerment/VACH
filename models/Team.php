<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Team extends ActiveRecord {

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['name', 'coach_id', 'sponsor_id', 'company_id'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Name'),
            'coach_id' => Yii::t('team', 'Coach'),
            'sponsor_id' => Yii::t('team', 'Sponsor'),
            'company_id' => Yii::t('team', 'Company'),
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

    public function beforeValidate() {
        if (!isset($this->coach_id))
            $this->coach_id = Yii::$app->user->id;

        return parent::beforeValidate();
    }

    public function getCoach() {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getCompany() {
        return $this->hasOne(User::className(), ['id' => 'company_id']);
    }

    public function getSponsor() {
        return $this->hasOne(User::className(), ['id' => 'sponsor_id']);
    }

    public function getMembers() {
        return $this->hasMany(TeamMember::className(), ['team_id' => 'id']);
    }

    public function getAssessments() {
        return $this->hasMany(Assessment::className(), ['team_id' => 'id']);
    }

}
