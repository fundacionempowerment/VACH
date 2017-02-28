<?php

use yii\db\Migration;

class m170228_044825_add_currency extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'from_currency' => $this->string(20)->notNull(),
            'to_currency' => $this->string(20)->notNull(),
            'rate' => $this->decimal(10, 4)->notNull(),
            'stamp' => $this->dateTime()->notNull(),
                ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%currency}}');
    }

}
