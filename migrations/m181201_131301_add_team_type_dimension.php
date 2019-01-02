<?php

use yii\db\Migration;

/**
 * Class m181201_131301_add_team_type_dimension
 */
class m181201_131301_add_team_type_dimension extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%team_type_dimension}}', [
            'id' => $this->primaryKey(),
            'team_type_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'order' => $this->integer()->notNull(),
            'level' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_team_type_dimension_team_type', 'team_type_dimension', 'team_type_id', 'team_type', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('fk_team_type_dimension_team_type', 'team_type_dimension');
        $this->dropTable('{{%team_type_dimension}}');
    }

}
