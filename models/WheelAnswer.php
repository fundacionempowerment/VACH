<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\WheelAnswer;

/**
 * LoginForm is the model behind the login form.
 */
class WheelAnswer extends ActiveRecord {

    const ANSWER_NUMBERS_0_TO_4 = 0;
    const ANSWER_WORST_TO_OPTIMAL = 1;
    const ANSWER_NEVER_TO_ALWAYS = 2;
    const ANSWER_NONE_TO_ALL = 3;
    const ANSWER_NONE_TO_ABSOLUTLY = 4;
    const ANSWER_OPTIMAL_TO_WORST = 101;
    const ANSWER_ALWAYS_TO_NEVER = 102;
    const ANSWER_ALL_TO_NONE = 103;
    const ANSWER_ABSOLUTLY_TO_NONE = 104;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%wheel_answer}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['answer_order', 'answer_value'], 'required'],
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
            'answer_order' => Yii::t('wheel', 'Order'),
            'answer_value' => Yii::t('wheel', 'Value'),
        ];
    }

    public static function getAnswerLabels($setName) {
        switch ($setName) {
            case WheelAnswer::ANSWER_NUMBERS_0_TO_4:
                return ['0', '1', '2', '3', '4'];
            case WheelAnswer::ANSWER_WORST_TO_OPTIMAL:
                return [
                    Yii::t('wheel', 'worst'),
                    Yii::t('wheel', 'bad'),
                    Yii::t('wheel', 'fair'),
                    Yii::t('wheel', 'good'),
                    Yii::t('wheel', 'optimal')
                ];
            case WheelAnswer::ANSWER_OPTIMAL_TO_WORST:
                return array_reverse(WheelAnswer::getAnswerLabels(WheelAnswer::ANSWER_WORST_TO_OPTIMAL));
            case WheelAnswer::ANSWER_NONE_TO_ALL:
                return [
                    Yii::t('wheel', 'none'),
                    Yii::t('wheel', 'few'),
                    Yii::t('wheel', 'some'),
                    Yii::t('wheel', 'many'),
                    Yii::t('wheel', 'all')
                ];
            case WheelAnswer::ANSWER_ALL_TO_NONE:
                return array_reverse(WheelAnswer::getAnswerLabels(WheelAnswer::ANSWER_NONE_TO_ALL));
            case WheelAnswer::ANSWER_NEVER_TO_ALWAYS:
                return [
                    Yii::t('wheel', 'never'),
                    Yii::t('wheel', 'sometimes'),
                    Yii::t('wheel', 'often'),
                    Yii::t('wheel', 'usually'),
                    Yii::t('wheel', 'always')
                ];
            case WheelAnswer::ANSWER_ALWAYS_TO_NEVER:
                return array_reverse(WheelAnswer::getAnswerLabels(WheelAnswer::ANSWER_NEVER_TO_ALWAYS));

            case WheelAnswer::ANSWER_NONE_TO_ABSOLUTLY:
                return [
                    Yii::t('wheel', 'none'),
                    Yii::t('wheel', 'rarely'),
                    Yii::t('wheel', 'regularly'),
                    Yii::t('wheel', 'mostly'),
                    Yii::t('wheel', 'absolutly')
                ];
            case WheelAnswer::ANSWER_ABSOLUTLY_TO_NONE:
                return array_reverse(WheelAnswer::getAnswerLabels(WheelAnswer::ANSWER_NONE_TO_ABSOLUTLY));
        }
    }

}
