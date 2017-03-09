<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\db\Expression;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Stock extends ActiveRecord
{

    const STATUS_INVALID = 'invalid';
    const STATUS_VALID = 'valid';
    const STATUS_ERROR = 'error';

    public function init()
    {
        parent::init();

        $this->stamp = date('Y-m-d H:i:s');
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['coach_id', 'product_id', 'quantity', 'price', 'total', 'status', 'stamp'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'coach_id' => Yii::t('team', 'Coach'),
            'product_id' => Yii::t('stock', 'Product'),
            'quantity' => Yii::t('stock', 'Quantity'),
            'price' => Yii::t('stock', 'Price'),
            'total' => Yii::t('stock', 'Total'),
            'status' => Yii::t('app', 'Status'),
            'statusName' => Yii::t('app', 'Status'),
            'stamp' => Yii::t('app', 'Date and Time'),
            'payments' => Yii::t('payment', 'Payments'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
    }

    public function beforeValidate()
    {
        if (!isset($this->coach_id)) {
            $this->coach_id = Yii::$app->user->id;
        }

        return parent::beforeValidate();
    }

    public static function browse()
    {
        return Stock::find()->where([
                    'coach_id' => Yii::$app->user->id,
                    'status' => self::STATUS_VALID,
                ])->orderBy('id desc');
    }

    public function getCoach()
    {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['stock_id' => 'id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public static function getStatusList()
    {
        $list = [
            self::STATUS_INVALID => Yii::t('app', self::STATUS_INVALID),
            self::STATUS_VALID => Yii::t('app', self::STATUS_VALID),
            self::STATUS_ERROR => Yii::t('app', self::STATUS_ERROR),
        ];

        return $list;
    }

    public function getStatusName()
    {
        return self::getStatusList()[$this->status];
    }

    public static function getStock($product_id)
    {
        $query = new Query();

        $balance = $query->select(new Expression('sum(quantity) as balance'))
                ->from('stock')
                ->where([
                    'coach_id' => Yii::$app->user->id,
                    'product_id' => $product_id,
                    'status' => self::STATUS_VALID,
                ])
                ->one();

        if ($balance && $balance['balance']) {
            return $balance['balance'];
        }
        return 0;
    }

}
