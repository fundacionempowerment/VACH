<?php

use yii\db\Migration;

class m170325_050124_add_question_table extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'text' => $this->text()->notNull(),
                ], $tableOptions);

        $this->execute('INSERT INTO `question` (`text`)
            SELECT DISTINCT `wheel_question`.`question`
            FROM `wheel_question`');

        $this->addColumn('wheel_question', 'question_id', $this->integer()->null());

        $this->execute('UPDATE `wheel_question` SET `question_id`= ( '
                . 'SELECT `question`.`id` '
                . 'FROM `question` '
                . 'WHERE `question`.`text` = `wheel_question`.`question` )');

        $this->alterColumn('wheel_question', 'question_id', $this->integer()->notNull());

        $this->dropColumn('wheel_question', 'question');
    }

    public function down()
    {
        $this->addColumn('wheel_question', 'question', $this->text()->null());

        $this->execute('UPDATE `wheel_question` SET `question`= ( '
                . 'SELECT `question`.`text` '
                . 'FROM `question` '
                . 'WHERE `question`.`id` = `wheel_question`.`question_id` )');

        $this->alterColumn('wheel_question', 'question', $this->text()->notNull());

        $this->dropColumn('wheel_question', 'question_id');

        $this->dropTable('{{%question}}');
    }

}
