<?php

use yii\db\Migration;

class m170820_204226_add_team_type_tables extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('team_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'product_id' => $this->integer()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_team_type_product', 'team_type', 'product_id', 'product', 'id');

        // Populate table

        $this->insert('team_type', ['name' => 'Empresa', 'product_id' => 1]);

        // Alter team

        $this->addColumn('team', 'team_type_id', $this->integer());
        $this->update('team', ['team_type_id' => 1]);
        $this->alterColumn('team', 'team_type_id', $this->integer()->notNull());
        $this->addForeignKey('fk_team_team_type', 'team', 'team_type_id', 'team_type', 'id');

        // Alter questions

        $this->addColumn('wheel_question', 'team_type_id', $this->integer());
        $this->update('wheel_question', ['team_type_id' => 1]);
        $this->alterColumn('wheel_question', 'team_type_id', $this->integer()->notNull());
        $this->addForeignKey('fk_wheel_question_team_type', 'wheel_question', 'team_type_id', 'team_type', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_wheel_question_team_type', 'wheel_question');
        $this->dropColumn('wheel_question', 'team_type_id');
        $this->dropForeignKey('fk_team_team_type', 'team');
        $this->dropColumn('team', 'team_type_id');
        $this->dropTable('team_type');
    }

}
