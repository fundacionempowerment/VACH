<?php

use yii\db\Migration;

class m170227_044825_add_user_account extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%account}}', [
            'id' => $this->primaryKey(),
            'coach_id' => $this->integer()->notNull(),
            'concept' => $this->string()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'payment_id' => $this->integer(),
            'stamp' => $this->dateTime()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_account_coach', '{{%account}}', 'coach_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_account_payment', '{{%account}}', 'payment_id', '{{%payment}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_account_coach', '{{%account}}');
        $this->dropForeignKey('fk_account_payment', '{{%account}}');
        $this->dropTable('{{%account}}');
    }

}
