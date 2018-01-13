<?php

use yii\db\Migration;

class m170404_030521_add_assessment_coach extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%assessment_coach}}', [
            'id' => $this->primaryKey(),
            'assessment_id' => $this->integer()->notNull(),
            'coach_id' => $this->integer()->notNull(),
                ], $tableOptions);

        $this->execute('INSERT INTO `assessment_coach` (`assessment_id`, `coach_id`)
            SELECT DISTINCT `assessment`.`id`, `team`.`coach_id`
            FROM `assessment`
                INNER JOIN `team`
                    ON `team`.`id` = `assessment`.`team_id`');

        $this->addForeignKey('fk_assessment_coach_assessment', 'assessment_coach', 'assessment_id', 'assessment', 'id');
        $this->addForeignKey('fk_assessment_coach_coach', 'assessment_coach', 'coach_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropTable('assessment_coach');
    }

}
