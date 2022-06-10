<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string $uuid
 * @property int $payment_id
 * @property string $status
 * @property string $external_id
 * @property string $stamp
 * @property string $amount
 * @property string $currency
 * @property string $rate
 * @property string $commision
 * @property string $commision_currency
 *
 * @property Payment $payment
 * @property TransactionLog[] $transactionLogs
 */
class Transaction extends ActiveRecord {

    public $external_data;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'transaction';
    }

    public function init() {
        parent::init();
        $this->uuid = uniqid('', true);
        $this->stamp = date('Y-m-d H:i:s');
        $this->status = Payment::STATUS_PENDING;
        if (!Yii::$app->user->isGuest){
            $this->creator_id = Yii::$app->user->id;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['uuid', 'payment_id', 'stamp', 'amount'], 'required'],
            [['payment_id'], 'integer'],
            [['status'], 'string'],
            [['stamp'], 'safe'],
            [['amount', 'rate', 'commision'], 'number'],
            [['uuid'], 'string', 'max' => 255],
            [['external_id'], 'string', 'max' => 50],
            [['currency', 'commision_currency'], 'string', 'max' => 3],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::class, 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'payment_id' => 'Payment ID',
            'status' => 'Status',
            'external_id' => 'External ID',
            'stamp' => Yii::t('app', 'Date and Time'),
            'amount' => 'Amount',
            'currency' => 'Currency',
            'rate' => 'Rate',
            'commision' => 'Commision',
            'commision_currency' => 'Commision Currency',
            'statusName' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        $log = new TransactionLog();
        $log->transaction_id = $this->id;
        $log->status = $this->status;
        $log->external_id = $this->external_id;
        $log->external_data = $this->external_data;
        $log->stamp = date('Y-m-d H:i:s');
        if (!Yii::$app->user->isGuest) {
            $log->creator_id = Yii::$app->user->id;
        }

        if (!$log->save()) {
            \app\controllers\SiteController::FlashErrors($log);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment() {
        return $this->hasOne(Payment::class, ['id' => 'payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionLogs() {
        return $this->hasMany(TransactionLog::class, ['transaction_id' => 'id']);
    }

    public function getStatusName() {
        return Payment::getStatusList()[$this->status];
    }
}
