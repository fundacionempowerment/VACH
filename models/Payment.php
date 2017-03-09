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
    const STATUS_PARTIAL = 'partial';
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
            [['coach_id', 'uuid', 'concept', 'amount', 'status', 'stamp'], 'required'],
            [['external_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'coach_id' => Yii::t('team', 'Coach'),
            'uuid' => Yii::t('app', 'Unique ID'),
            'concept' => Yii::t('app', 'Concept'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'statusName' => Yii::t('app', 'Status'),
            'stamp' => Yii::t('app', 'Date and Time'),
            'logs' => Yii::t('app', 'Log'),
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

    public static function getStatusList()
    {
        $list = [
            self::STATUS_INIT => Yii::t('app', self::STATUS_INIT),
            self::STATUS_PENDING => Yii::t('app', self::STATUS_PENDING),
            self::STATUS_PAID => Yii::t('app', self::STATUS_PAID),
            self::STATUS_PARTIAL => Yii::t('app', self::STATUS_PARTIAL),
            self::STATUS_ERROR => Yii::t('app', self::STATUS_ERROR),
        ];

        return $list;
    }

    public function getStatusName()
    {
        return self::getStatusList()[$this->status];
    }

    public function getCoach()
    {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getLogs()
    {
        return $this->hasMany(PaymentLog::className(), ['payment_id' => 'id']);
    }

    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['id' => 'stock_id']);
    }

}
