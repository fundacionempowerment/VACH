<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\models\PaymentLog;

class Payment extends ActiveRecord
{

    const STATUS_INIT = 'init';
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ERROR = 'error';

    public $external_data;

    public function init()
    {
        parent::init();
        $this->uuid = uniqid('', true);
        $this->stamp = date('Y-m-d H:i:s');
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['coach_id', 'uuid', 'concept', 'amount', 'currency', 'status', 'stamp'], 'required'],
            [['external_id', 'rate', 'commision', 'commision_currency'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'coach_id' => Yii::t('team', 'Coach'),
            'uuid' => Yii::t('app', 'Unique ID'),
            'concept' => Yii::t('app', 'Concept'),
            'amount' => Yii::t('app', 'Trans. Amount'),
            'currency' => Yii::t('payment', 'Currency'),
            'rate' => Yii::t('payment', 'Rate'),
            'commision' => Yii::t('payment', 'Commision'),
            'commision_currency' => Yii::t('payment', 'Commision currency'),
            'status' => Yii::t('app', 'Status'),
            'is_manual' => Yii::t('payment', 'Is manual'),
            'statusName' => Yii::t('app', 'Status'),
            'stamp' => Yii::t('app', 'Date and Time'),
            'log' => Yii::t('app', 'Log'),
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $log = new PaymentLog();
        $log->payment_id = $this->id;
        $log->status = $this->status;
        $log->external_id = $this->external_id;
        $log->external_data = $this->external_data;
        $log->stamp = date('Y-m-d H:i:s');

        if (!$log->save()) {
            \app\controllers\SiteController::FlashErrors($log);
        }
    }

    public static function browse()
    {
        return Payment::find()->where(['coach_id' => Yii::$app->user->id])->orderBy('id desc');
    }

    public static function adminBrowse()
    {
        return Payment::find()->orderBy('id desc');
    }

    public static function adminBrowsePendings()
    {
        return Payment::find()
                        ->where(['status' => 'pending'])
                        ->andWhere(['>', 'amount', 0])
                        ->andWhere(['>', 'rate', 0])
                        ->orderBy('id desc');
    }

    public static function getStatusList()
    {
        $list = [
            self::STATUS_INIT => Yii::t('app', self::STATUS_INIT),
            self::STATUS_PENDING => Yii::t('app', self::STATUS_PENDING),
            self::STATUS_PAID => Yii::t('app', self::STATUS_PAID),
            self::STATUS_REJECTED => Yii::t('app', self::STATUS_REJECTED),
            self::STATUS_ERROR => Yii::t('app', self::STATUS_ERROR),
        ];

        return $list;
    }

    public function getName()
    {
        return Yii::t('payment', 'Payment') . ' ' . $this->id;
    }

    public function getLocalAmount()
    {
        return $this->amount * $this->rate;
    }

    public function getNetAmount()
    {
        return $this->amount * $this->rate - $this->commision;
    }

    public function getStatusName()
    {
        return self::getStatusList()[$this->status];
    }

    public function getCoach()
    {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    public function getLogs()
    {
        return $this->hasMany(PaymentLog::className(), ['payment_id' => 'id']);
    }

    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['id' => 'stock_id']);
    }

    public function getPart1Amount()
    {
        return $this->netAmount * $this->part_distribution / 100;
    }

    public function getPart2Amount()
    {
        return $this->netAmount * (100 - $this->part_distribution) / 100;
    }

}
