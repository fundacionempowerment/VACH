<?php

use yii\db\Migration;

class m170226_044825_add_payment extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'coach_id' => $this->integer()->notNull(),
            'uuid' => $this->string(50)->notNull(),
            'concept' => $this->string(255)->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'status' => "enum('init','pending','paid','partial','error') NOT NULL DEFAULT 'init'",
            'external_id' => $this->string(50),
            'stamp' => $this->dateTime()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_payment_coach', 'payment', 'coach_id', 'user', 'id');

        $this->createTable('{{%payment_log}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer()->notNull(),
            'status' => "enum('init','pending','paid','partial','error') NOT NULL DEFAULT 'init'",
            'external_id' => $this->text()->null(),
            'external_data' => $this->text()->null(),
            'stamp' => $this->dateTime()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_payment_log_payment', 'payment_log', 'payment_id', 'payment', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_payment_log_payment', 'payment_log');
        $this->dropTable('{{%payment_log}}');
        $this->dropForeignKey('fk_payment_coach', 'payment');
        $this->dropTable('{{%payment}}');
    }

}
