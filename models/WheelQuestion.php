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
    const ANSWER_NUMBERS_0_TO_4 = 0;
    const ANSWER_WORST_TO_OPTIMAL = 1;
    const ANSWER_NEVER_TO_ALWAYS = 2;
    const ANSWER_NONE_TO_ALL = 3;
    const ANSWER_OPTIMAL_TO_WORST = 101;
    const ANSWER_ALWAYS_TO_NEVER = 102;
    const ANSWER_ALL_TO_NONE = 103;

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

    public static function getAnswers($setName) {
        switch ($setName) {
            case WheelQuestion::ANSWER_NUMBERS_0_TO_4:
                return ['0', '1', '2', '3', '4'];
            case WheelQuestion::ANSWER_WORST_TO_OPTIMAL:
                return [
                    Yii::t('wheel', 'worst'),
                    Yii::t('wheel', 'bad'),
                    Yii::t('wheel', 'fair'),
                    Yii::t('wheel', 'good'),
                    Yii::t('wheel', 'optimal')
                ];
            case WheelQuestion::ANSWER_NONE_TO_ALL:
                return [
                    Yii::t('wheel', 'none'),
                    Yii::t('wheel', 'few'),
                    Yii::t('wheel', 'some'),
                    Yii::t('wheel', 'many'),
                    Yii::t('wheel', 'all')
                ];
            case WheelQuestion::ANSWER_NEVER_TO_ALWAYS:
                return [
                    Yii::t('wheel', 'never'),
                    Yii::t('wheel', 'sometimes'),
                    Yii::t('wheel', 'often'),
                    Yii::t('wheel', 'usually'),
                    Yii::t('wheel', 'always')
                ];
            case WheelQuestion::ANSWER_OPTIMAL_TO_WORST:
                return array_reverse(WheelQuestion::getAnswers(WheelQuestion::ANSWER_WORST_TO_OPTIMAL));
            case WheelQuestion::ANSWER_ALL_TO_NONE:
                return array_reverse(WheelQuestion::getAnswers(WheelQuestion::ANSWER_NONE_TO_ALL));
            case WheelQuestion::ANSWER_ALWAYS_TO_NEVER:
                return array_reverse(WheelQuestion::getAnswers(WheelQuestion::ANSWER_NEVER_TO_ALWAYS));
        }
    }

}
