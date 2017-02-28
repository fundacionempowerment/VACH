<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\db\Expression;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Account extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['coach_id', 'concept', 'amount', 'status', 'stamp'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'coach_id' => Yii::t('team', 'Coach'),
            'concept' => Yii::t('account', 'Concept'),
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

    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }

    public static function getBalance()
    {
        $query = new Query();

        $balance = $query->select(new Expression('sum(amount) as balance'))
                ->from('account')
                ->where(['coach_id' => Yii::$app->user->id])
                ->one();

        if ($balance && $balance['balance']) {
            return $balance['balance'];
        }
        return 0;
    }

}
