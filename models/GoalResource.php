<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class GoalResource extends ActiveRecord {

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['goal_id', 'is_desired', 'is_had', 'description'], 'required'],
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

}
