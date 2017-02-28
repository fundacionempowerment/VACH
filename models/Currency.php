<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord
{

    public function __construct()
    {
        $this->stamp = date('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['from_currency', 'to_currency', 'rate', 'stamp'], 'required'],
        ];
    }

    public function behaviors()
    {
        return [
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'from_currenct' => Yii::t('currency', 'From Currency'),
            'to_currency' => Yii::t('currency', 'To Currency'),
            'rate' => Yii::t('currency', 'Rate'),
        ];
    }

    public static function browse()
    {
        return Currency::find()->orderBy('id desc');
    }
    
    public static function lastValue()
    {
        return Currency::find()->orderBy('stamp desc')->one()->rate;
    }

}
