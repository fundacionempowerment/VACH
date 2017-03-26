<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'price', 'created_at', 'updated_at'], 'required'],
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
            'name' => Yii::t('app', 'Name'),
            'price' => Yii::t('product', 'Price'),
        ];
    }

    public static function browse()
    {
        return Product::find()->orderBy('id desc');
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(static::browse()->all(), 'id', 'name');
    }

}
