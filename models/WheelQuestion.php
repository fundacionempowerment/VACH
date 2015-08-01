<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * LoginForm is the model behind the login form.
 */
class WheelQuestion extends ActiveRecord {

    const DIMENSION_FREE_TIME = 0;
    const DIMENSION_FAMILY = 1;
    const DIMENSION_WORK = 2;
    const DIMENSION_PHYSICAL = 3;
    const DIMENSION_EMOTIONAL = 4;
    const DIMENSION_MENTAL = 5;
    const DIMENSION_EXISTENTIAL = 6;
    const DIMENSION_SPIRITUAL = 7;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%wheel_question}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['dimension', 'order', 'question', 'answer_type'], 'required'],
        ];
    }

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'dimension' => Yii::t('wheel', 'Dimension'),
            'order' => Yii::t('wheel', 'Order'),
            'question' => Yii::t('wheel', 'Question'),
            'answer_type' => Yii::t('wheel', 'Answer type'),
        ];
    }

    public static function getDimensionNames($wheelType) {
        if ($wheelType == Wheel::TYPE_INDIVIDUAL)
            return [
                Yii::t('wheel', 'Free time'),
                Yii::t('wheel', 'Work'),
                Yii::t('wheel', 'Family'),
                Yii::t('wheel', 'Physical Dimension'),
                Yii::t('wheel', 'Emotional Dimension'),
                Yii::t('wheel', 'Mental Dimension'),
                Yii::t('wheel', 'Existential Dimension'),
                Yii::t('wheel', 'Spiritual Dimension'),
            ];
        else if ($wheelType == Wheel::TYPE_GROUP)
            return [
                Yii::t('wheel', 'Initiative'),
                Yii::t('wheel', 'Appropriateness'),
                Yii::t('wheel', 'Belonging'),
                Yii::t('wheel', 'Team work'),
                Yii::t('wheel', 'Flexibility'),
                Yii::t('wheel', 'Communication'),
                Yii::t('wheel', 'Leadership'),
                Yii::t('wheel', 'Legitimation'),
            ];
        else if ($wheelType == Wheel::TYPE_ORGANIZATIONAL)
            return [
                Yii::t('wheel', 'Creativity'),
                Yii::t('wheel', 'Results guidance'),
                Yii::t('wheel', 'Client guidance'),
                Yii::t('wheel', 'Quality guidance'),
                Yii::t('wheel', 'Change Management'),
                Yii::t('wheel', 'Conflict resolution'),
                Yii::t('wheel', 'Strategic vision'),
                Yii::t('wheel', 'Identity'),
            ];
    }

    public static function getQuestions($wheelType) {
        return self::find()->where(['type' => $wheelType])->asArray()->all();
    }

    public static function getQuestionCount($wheelType) {
        if ($wheelType == Wheel::TYPE_INDIVIDUAL)
            return 80;
        return 64;
    }

}
