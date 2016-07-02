<?php

use yii\db\Migration;

class m160701_033529_add_company extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'coach_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_company_coach', '{{%company}}', 'coach_id', '{{%user}}', 'id');
    }

    public function down() {
        $this->dropForeignKey('fk_company_coach', '{{%company}}');

        $this->dropTable('{{%company}}');
    }

}
