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
