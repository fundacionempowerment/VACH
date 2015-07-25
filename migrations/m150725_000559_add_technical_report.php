<?php

use yii\db\Schema;
use yii\db\Migration;

class m150725_000559_add_technical_report extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%report}}', [
            'id' => Schema::TYPE_PK,
            'assessment_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'introduction' => Schema::TYPE_TEXT . ' NOT NULL',
            'consciousness' => Schema::TYPE_TEXT . ' NOT NULL',
            'potential_matrix' => Schema::TYPE_TEXT . ' NOT NULL',
            'indicators' => Schema::TYPE_TEXT . ' NOT NULL',
            'emergents' => Schema::TYPE_TEXT . ' NOT NULL',
            'summary' => Schema::TYPE_TEXT . ' NOT NULL',
            'action_plan' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->addForeignKey('fk_report_assessment', '{{%report}}', 'assessment_id', '{{%assessment}}', 'id', 'CASCADE');

        $this->createTable('{{%individual_report}}', [
            'id' => Schema::TYPE_PK,
            'report_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'perception_adjustment_matrix' => Schema::TYPE_TEXT . ' NOT NULL',
            'relations' => Schema::TYPE_TEXT . ' NOT NULL',
            'indicators' => Schema::TYPE_TEXT . ' NOT NULL',
            'emergents' => Schema::TYPE_TEXT . ' NOT NULL',
            'summary' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->addForeignKey('fk_individual_report_report', '{{%individual_report}}', 'report_id', '{{%report}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_individual_report_user', '{{%individual_report}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down() {
        $this->dropForeignKey('fk_individual_report_report', '{{%individual_report}}');
        $this->dropForeignKey('fk_individual_report_user', '{{%individual_report}}');
        $this->dropTable('{{%individual_report}}');

        $this->dropForeignKey('fk_report_assessment', '{{%report}}');
        $this->dropTable('{{%report}}');
    }

}
