<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class GoalMilestone extends ActiveRecord {

    const TYPE_LOW = 0;
    const TYPE_MEDIUM = 1;
    const TYPE_HIGH = 2;

    public function __construct() {
        $this->date = date("Y-m-d");
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['goal_id', 'description', 'type', 'evidence', 'date', 'celebration'], 'required'],
            [['goal_id', 'description', 'type', 'evidence', 'date', 'celebration'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'description' => Yii::t('app', 'Description'),
            'type' => Yii::t('app', 'Type'),
            'date' => Yii::t('app', 'Date'),
            'evidence' => Yii::t('goal', 'Evidence'),
            'celebration' => Yii::t('goal', 'Celebration'),
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

    public function getGoal() {
        return $this->hasOne(Goal::className(), ['id' => 'goal_id']);
    }

    public static function getTypes() {
        return [
            GoalMilestone::TYPE_HIGH => Yii::t('app', 'High'),
            GoalMilestone::TYPE_MEDIUM => Yii::t('app', 'Medium'),
            GoalMilestone::TYPE_LOW => Yii::t('app', 'Low'),
        ];
    }

    public static function getPlan($coachee_id) {
        return GoalMilestone::find()
                        ->innerJoin('goal', 'goal.id = goal_milestone.goal_id')
                        ->where(['coachee_id' => $coachee_id])
                        ->orderBy('date')
                        ->all();
    }

}
