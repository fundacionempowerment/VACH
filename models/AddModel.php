<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "article".
 *
 * @property double $amount
 * 
 */
class AddModel extends Model
{

    public $product_id;
    public $quantity;
    public $price;
    public $coach_id;
    public $part_distribution;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['product_id', 'quantity', 'price', 'coach_id', 'part_distribution'], 'required'],
            ['quantity', 'number', 'min' => 1, 'max' => 100],
            ['amount', 'number', 'min' => 0, 'max' => 100000],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'price' => Yii::t('stock', 'Price in USD $'),
            'quantity' => Yii::t('stock', 'Quantity'),
            'coach_id' => Yii::t('app', 'Coach'),
        ];
    }

}
