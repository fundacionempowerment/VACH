<?php

use yii\db\Migration;

class m170511_002445_add_session_token extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_session}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull(),
            'stamp' => $this->string()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_user_session_user', 'user_session', 'user_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_user_session_user', 'user_session');

        $this->dropTable('session_token');
    }

}
