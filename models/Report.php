<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Report extends ActiveRecord {

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['assessment_id'], 'required'],
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

    public function getIndividualReports() {
        return $this->hasMany(IndividualReport::className(), ['report_id' => 'id']);
    }

    public function getAssessment() {
        return $this->hasOne(Assessment::className(), ['id' => 'assessment_id']);
    }

}
