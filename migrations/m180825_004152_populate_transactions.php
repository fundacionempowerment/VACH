<?php

use yii\db\Migration;

/**
 * Class m180825_004152_populate_transactions
 */
class m180825_004152_populate_transactions extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $payments = $this->db->createCommand("SELECT * FROM `payment`")->queryAll();

        foreach ($payments as $payment) {
            $this->insert('transaction', [
                'uuid' => uniqid('', true),
                'payment_id' => $payment['id'],
                'status' => $payment['status'],
                'external_id' => $payment['external_id'],
                'stamp' => $payment['stamp'],
                'amount' => $payment['amount'],
                'currency' => $payment['currency'],
                'rate' => $payment['rate'],
                'commision' => $payment['commision'],
                'commision_currency' => $payment['commision_currency'],
            ]);
            $transaction_id = Yii::$app->db->getLastInsertID();

            $payment_logs = $this->db->createCommand("SELECT * FROM `payment_log` WHERE `payment_id` = " . $payment['id'])->queryAll();

            foreach ($payment_logs as $payment_log) {
                $this->insert('transaction_log', [
                    'transaction_id' => $transaction_id,
                    'status' => $payment_log['status'],
                    'external_id' => $payment_log['external_id'],
                    'external_data' => $payment_log['external_data'],
                    'stamp' => $payment_log['stamp'],
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180825_004152_populate_transactions cannot be reverted.\n";

        return false;
    }

}
