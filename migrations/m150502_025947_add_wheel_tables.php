<?php

use yii\db\Schema;
use yii\db\Migration;

class m150502_025947_add_wheel_tables extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wheel}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->addForeignKey('fk_wheel_user', '{{%wheel}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->createTable('{{%wheel_answer}}', [
            'id' => Schema::TYPE_PK,
            'wheel_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'answer_order' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'answer_value' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->addForeignKey('fk_wheel_answer_wheel', '{{%wheel_answer}}', 'wheel_id', '{{%wheel}}', 'id', 'CASCADE');
    }

    public function down() {
        $this->dropTable('{{%wheel_answer}}');
        $this->dropTable('{{%wheel}}');
    }

}
