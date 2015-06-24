<?php

use yii\db\Schema;
use yii\db\Migration;

class m150609_053234_add_team_tables extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%team}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'coach_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sponsor_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'company_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->addForeignKey('fk_team_coach', '{{%team}}', 'coach_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_team_sponsor', '{{%team}}', 'sponsor_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_team_company', '{{%team}}', 'company_id', '{{%user}}', 'id');

        $this->createTable('{{%team_member}}', [
            'id' => Schema::TYPE_PK,
            'team_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->addForeignKey('fk_team_member_team', '{{%team_member}}', 'team_id', '{{%team}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_team_member_user', '{{%team_member}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down() {
        $this->dropForeignKey('fk_team_member_team', '{{%team_member}}');
        $this->dropForeignKey('fk_team_member_user', '{{%team_member}}');

        $this->dropTable('{{%team_member}}');

        $this->dropForeignKey('fk_team_coach', '{{%team}}');
        $this->dropForeignKey('fk_team_sponsor', '{{%team}}');
        $this->dropForeignKey('fk_team_company', '{{%team}}');

        $this->dropTable('{{%team}}');
    }

}
