<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\WheelAnswer;

/**
 * LoginForm is the model behind the login form.
 */
class WheelAnswer extends ActiveRecord
{

    const ANSWER_NUMBERS_0_TO_4 = 0;
    const ANSWER_WORST_TO_OPTIMAL = 1;
    const ANSWER_NEVER_TO_ALWAYS = 2;
    const ANSWER_NONE_TO_ALL = 3;
    const ANSWER_NOTHING_TO_ABSOLUTLY = 4;
    const ANSWER_OPTIMAL_TO_WORST = 101;
    const ANSWER_ALWAYS_TO_NEVER = 102;
    const ANSWER_ALL_TO_NONE = 103;
    const ANSWER_ABSOLUTLY_TO_NOTHING = 104;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wheel_answer}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['answer_order', 'answer_value'], 'required'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'answer_order' => Yii::t('wheel', 'Order'),
            'answer_value' => Yii::t('wheel', 'Value'),
        ];
    }

    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

}
