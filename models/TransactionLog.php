<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction_log".
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $creator_id
 * @property string $status
 * @property string $external_id
 * @property string $external_data
 * @property string $stamp
 *
 * @property Transaction $transaction
 */
class TransactionLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'stamp'], 'required'],
            [['transaction_id'], 'integer'],
            [['status', 'external_id', 'external_data'], 'string'],
            [['stamp'], 'safe'],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::class, 'targetAttribute' => ['transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'status' => 'Status',
            'external_id' => 'External ID',
            'external_data' => 'External Data',
            'stamp' => 'Stamp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::class, ['id' => 'transaction_id']);
    }
}
