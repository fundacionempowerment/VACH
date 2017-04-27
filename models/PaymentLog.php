<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;
use app\models\Payment;

class PaymentLog extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['payment_id', 'status', 'stamp'], 'required'],
            [['external_id', 'external_data'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'payment_id' => Yii::t('payment', 'Payment'),
            'status' => Yii::t('app', 'Status'),
            'statusName' => Yii::t('app', 'Status'),
            'stamp' => Yii::t('app', 'Date and Time'),
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

    public static function getStatusList()
    {
        return Payment::getStatusList();
    }

    public function getStatusName()
    {
        return self::getStatusList()[$this->status];
    }

}
