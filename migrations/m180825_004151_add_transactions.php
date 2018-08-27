<?php

use yii\db\Migration;

/**
 * Class m180825_004151_add_transactions
 */
class m180825_004151_add_transactions extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'uuid' => $this->string()->notNull(),
            'payment_id' => $this->integer()->notNull(),
            'status' => "enum('init','pending','paid','rejected','error') NOT NULL DEFAULT 'init'",
            'external_id' => $this->string(50),
            'stamp' => $this->dateTime()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'currency' => $this->string(3)->notNull()->defaultValue('USD'),
            'rate' => $this->decimal(10, 2)->null(),
            'commision' => $this->decimal(10, 2)->null(),
            'commision_currency' => $this->string(3)->null(),
        ], $tableOptions);

        $this->addForeignKey('fk_transaction_payment', 'transaction', 'payment_id', 'payment', 'id');

        $this->createTable('{{%transaction_log}}', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->integer()->notNull(),
            'status' => "enum('init','pending','paid','rejected','error') NOT NULL DEFAULT 'init'",
            'external_id' => $this->text()->null(),
            'external_data' => $this->text()->null(),
            'stamp' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_transaction_log_transaction', 'transaction_log', 'transaction_id', 'transaction', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('fk_transaction_log_transaction', 'transaction_log');
        $this->dropTable('transaction_log');
        $this->dropForeignKey('fk_transaction_payment', 'transaction');
        $this->dropTable('transaction');
    }

}
