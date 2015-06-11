<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Assessment extends ActiveRecord {

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
        $this->name = $this->id . ' - ' . $this->team->company->name . ' ' . $this->team->name . ' ';
    }

    public function getTeam() {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

}
