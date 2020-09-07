<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{

    public $deletable;

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
            [['name', 'price'], 'required'],
            [['description'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
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
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public static function browse()
    {
        return Product::find()->orderBy('id desc');
    }

    public function afterFind()
    {
        $stocks = $this->hasMany(Stock::class, ['product_id' => 'id']);
        $this->deletable = $stocks->count() == 0;

        parent::afterFind();
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(static::browse()->all(), 'id', 'name');
    }

}
