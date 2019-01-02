<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @package app\models
 * @property integer id
 * @property integer wheel_id
 * @property integer answer_value
 * @property integer answer_order
 * @property integer dimension
 * @property integer question_id
 * @property integer created_at
 * @property integer updated_at
 *
 * @property Wheel wheel
 * @property Question question
 */
class WheelAnswer extends ActiveRecord
{

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
