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
            [['report_id', 'user_id'], 'required'],
        ];
    }

    public function getMember() {
        return $this->hasOne(Person::className(), ['id' => 'user_id']);
    }

}
