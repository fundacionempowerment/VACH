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

}
