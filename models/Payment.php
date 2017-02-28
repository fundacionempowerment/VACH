<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Payment extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['coach_id', 'uuid', 'concept', 'amount', 'status', 'stamp'], 'required'],
            [['external_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'coach_id' => Yii::t('team', 'Coach'),
            'uuid' => Yii::t('app', 'Unique ID'),
            'concept' => Yii::t('account', 'Concept'),
            'amount' => Yii::t('account', 'Amount'),
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

    public function beforeValidate()
    {
        if (!isset($this->coach_id)) {
            $this->coach_id = Yii::$app->user->id;
        }

        return parent::beforeValidate();
    }

    public static function browse()
    {
        return Payment::find()->where(['coach_id' => Yii::$app->user->id])->orderBy('id desc');
    }

    public function getCoach()
    {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getPaymentLogs()
    {
        return $this->hasMany(PaymentLog::className(), ['payment_id' => 'id']);
    }

}
