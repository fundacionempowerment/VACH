<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class IndividualReport extends ActiveRecord {

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['report_id', 'person_id'], 'required'],
        ];
    }

    public function getMember() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    public function getReport() {
        return $this->hasOne(Report::className(), ['id' => 'report_id']);
    }

}
