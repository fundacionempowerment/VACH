<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\models\LiquidationLog;

class Liquidation extends ActiveRecord
{

    public function init()
    {
        parent::init();
        $this->currency = 'ARS';
        $this->stamp = date('Y-m-d H:i:s');
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['stamp', 'currency', 'raw_amount', 'commision', 'net_amount', 'part1_amount', 'part2_amount'], 'required'],
            [['raw_amount', 'commision', 'net_amount', 'part1_amount', 'part2_amount'], 'number', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'payments' => Yii::t('payment', 'Payments'),
            'stamp' => Yii::t('app', 'Date and Time'),
            'currency' => Yii::t('payment', 'Currency'),
            'raw_amount' => Yii::t('payment', 'Raw amount'),
            'commision' => Yii::t('payment', 'Commision'),
            'net_amount' => Yii::t('payment', 'Net amount'),
            'part1_amount' => Yii::t('payment', '{name} amount', ['name' => Yii::$app->params['part1_name']]),
            'part2_amount' => Yii::t('payment', '{name} amount', ['name' => Yii::$app->params['part2_name']]),
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

    public static function adminBrowse()
    {
        return Liquidation::find()->orderBy('id desc');
    }

    public function getName()
    {
        return Yii::t('payment', 'Liquidation') . ' ' . $this->id;
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['liquidation_id' => 'id'])
                ->orderBy(['id' => SORT_DESC]);
    }

    public function getAvailablePayments()
    {
        return Payment::find()
                        ->where('liquidation_id is null')
                        ->orWhere(['liquidation_id' => $this->id])
                        ->andWhere(['status' => Payment::STATUS_PAID])
                        ->andWhere(['>', 'amount', '0'])
                        ->orderBy(['id' => SORT_DESC]);
    }

}
