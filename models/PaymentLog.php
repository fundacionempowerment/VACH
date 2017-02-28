<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

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
            'payment_id' => Yii::t('account', 'Payment'),
            'status' => Yii::t('app', 'Status'),
            'stamp' => Yii::t('app', 'Date and time'),
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

}
