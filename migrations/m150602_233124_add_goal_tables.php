<?php

use yii\db\Schema;
use yii\db\Migration;

class m150602_233124_add_goal_tables extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%goal}}', [
            'id' => Schema::TYPE_PK,
            'coachee_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->createTable('{{%goal_milestone}}', [
            'id' => Schema::TYPE_PK,
            'goal_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'evidence' => Schema::TYPE_TEXT . ' NOT NULL',
            'date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'celebration' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->createTable('{{%goal_resource}}', [
            'id' => Schema::TYPE_PK,
            'goal_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'is_desired' => Schema::TYPE_BOOLEAN,
            'is_had' => Schema::TYPE_BOOLEAN,
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%goal_resource}}');
        $this->dropTable('{{%goal_milestone}}');
        $this->dropTable('{{%goal}}');
    }

}
