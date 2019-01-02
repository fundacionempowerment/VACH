<?php

use yii\db\Migration;

/**
 * Class m180829_234218_add_transactions_fk
 */
class m180829_234218_add_transactions_fk extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addForeignKey('fk_transaction_creator', 'transaction', 'creator_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('fk_transaction_creator', 'transaction');
    }

}
