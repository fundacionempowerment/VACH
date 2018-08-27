<?php

use yii\db\Migration;

/**
 * Class m180827_024140_add_payment_log_creator
 */
class m180827_024140_add_payment_log_creator extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('payment_log', 'creator_id', $this->integer());
        $this->addForeignKey('fk_payment_log_creator', 'payment_log', 'creator_id', 'user', 'id');
        $this->execute("update payment_log set creator_id = (SELECT payment.creator_id FROM payment where payment.id = payment_log.payment_id)");

        $this->addColumn('transaction', 'creator_id', $this->integer());
        $this->execute("update `transaction` set creator_id = (SELECT `payment`.creator_id FROM `payment` where `payment`.id = `transaction`.payment_id)");

        $this->addColumn('transaction_log', 'creator_id', $this->integer());
        $this->addForeignKey('fk_transaction_log_creator', 'transaction_log', 'creator_id', 'user', 'id');
        $this->execute("update transaction_log set creator_id = (SELECT `transaction`.creator_id FROM `transaction` where `transaction`.id = transaction_log.transaction_id)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('fk_payment_log_creator', 'payment_log');
        $this->dropColumn('payment_log', 'creator_id');

        $this->dropForeignKey('fk_transaction_log_creator', 'transaction_log');
        $this->dropColumn('transaction_log', 'creator_id');

        $this->dropColumn('transaction', 'creator_id');
    }
}
