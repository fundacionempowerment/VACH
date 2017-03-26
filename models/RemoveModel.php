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
class RemoveModel extends Model
{

    public $product_id;
    public $quantity;
    public $coach_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['product_id', 'quantity', 'coach_id'], 'required'],
            ['quantity', 'number', 'min' => 1, 'max' => 100],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'quantity' => Yii::t('stock', 'Quantity'),
            'coach_id' => Yii::t('app', 'Coach'),
        ];
    }

}
