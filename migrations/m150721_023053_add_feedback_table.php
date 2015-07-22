<?php

use yii\db\Schema;
use yii\db\Migration;

class m150721_023053_add_feedback_table extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%feedback}}', [
            'id' => Schema::TYPE_PK,
            'ip' => Schema::TYPE_STRING . ' NOT NULL',
            'effectiveness' => 'TINYINT NOT NULL',
            'efficience' => 'TINYINT NOT NULL',
            'satisfaction' => 'TINYINT NOT NULL',
            'comment' => Schema:: TYPE_STRING,
            'datetime' => Schema::TYPE_DATETIME . ' NOT NULL',
                ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%feedback}}');
    }

}
