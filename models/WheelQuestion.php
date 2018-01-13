<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * LoginForm is the model behind the login form.
 */
class WheelQuestion extends ActiveRecord
{

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
    public static function tableName()
    {
        return '{{%wheel_question}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['dimension', 'order', 'question'], 'required'],
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
            'dimension' => Yii::t('wheel', 'Dimension'),
            'order' => Yii::t('wheel', 'Order'),
            'question' => Yii::t('wheel', 'Question'),
        ];
    }

    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    public static function getDimentionName($dimensionOrder, $wheelType, $short = false)
    {
        $dimensions = self::getDimensionNames($wheelType, $short);
        return $dimensions[$dimensionOrder];
    }

    public static function getDimensionNames($wheelType, $short = false)
    {
        if ($wheelType == Wheel::TYPE_INDIVIDUAL) {
            $dimensions = [
                Yii::t('wheel', 'Free time'),
                Yii::t('wheel', 'Work'),
                Yii::t('wheel', 'Family'),
                Yii::t('wheel', 'Physical Dimension'),
                Yii::t('wheel', 'Emotional Dimension'),
                Yii::t('wheel', 'Mental Dimension'),
                Yii::t('wheel', 'Existential Dimension'),
                Yii::t('wheel', 'Spiritual Dimension'),
            ];
        } else if ($wheelType == Wheel::TYPE_GROUP) {
            $dimensions = [
                Yii::t('wheel', 'Initiative'),
                Yii::t('wheel', 'Appropriateness'),
                Yii::t('wheel', 'Belonging'),
                Yii::t('wheel', 'Team work'),
                Yii::t('wheel', 'Flexibility'),
                Yii::t('wheel', 'Communication'),
                Yii::t('wheel', 'Leadership'),
                Yii::t('wheel', 'Legitimation'),
            ];
        } else {
            $dimensions = [
                Yii::t('wheel', 'Creativity'),
                Yii::t('wheel', 'Results guidance'),
                Yii::t('wheel', 'Client guidance'),
                Yii::t('wheel', 'Quality guidance'),
                Yii::t('wheel', 'Conflict resolution'),
                Yii::t('wheel', 'Change Management'),
                Yii::t('wheel', 'Strategic vision'),
                Yii::t('wheel', 'Identity'),
            ];
        }
        if ($short) {
            for ($i = 0; $i < count($dimensions); $i++) {
                $dimensions[$i] = str_replace('Orientación', 'O.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Orientation', 'O.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Resolución', 'Res.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Dimensión', 'D.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Gestión', 'G.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Visión', 'V.', $dimensions[$i]);
                $dimensions[$i] = str_replace('estratégica', 'estrat.', $dimensions[$i]);
            }
        }
        return $dimensions;
    }

    public static function getQuestions($wheel)
    {
        return $wheel->team->teamType->getWheelQuestions()->where(['type' => $wheel->type])->all();
    }

    public static function getQuestionCount($wheelType)
    {
        return $wheelType == Wheel::TYPE_INDIVIDUAL ? 80 : 64;
    }

}
