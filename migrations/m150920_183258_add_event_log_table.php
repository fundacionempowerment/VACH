<?php

use yii\db\Schema;
use yii\db\Migration;

class m150920_183258_add_event_log_table extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%log}}', [
            'id' => Schema::TYPE_PK,
            'coach_id' => $this->integer(),
            'text' => $this->text(),
            'datetime' => $this->dateTime(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_log_user', '{{%log}}', 'coach_id', '{{%user}}', 'id');
    }

    public function down() {
        $this->dropForeignKey('fk_log_user', '{{%log}}');
        $this->dropTable('{{%log}}');
    }

}
