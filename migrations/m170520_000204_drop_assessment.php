<?php

use yii\db\Migration;

class m170520_000204_drop_assessment extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('tempo_team', [
            'id' => $this->primaryKey(),
            'original_team_id' => $this->integer()->notNull(),
            'original_assessment_id' => $this->integer()->null(),
            'name' => $this->string()->notNull(),
            'sponsor_id' => $this->integer()->notNull(),
            'company_id' => $this->integer()->notNull(),
            'coach_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);

        $this->createTable('tempo_team_member', [
            'id' => $this->primaryKey(),
            'original_team_id' => $this->integer()->notNull(),
            'original_assessment_id' => $this->integer()->null(),
            'team_id' => $this->integer()->notNull(),
            'person_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);

        $this->createTable('tempo_team_coach', [
            'id' => $this->primaryKey(),
            'team_id' => $this->integer()->notNull(),
            'coach_id' => $this->integer()->notNull(),
            'anonimize' => $this->boolean()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);


        $this->execute("
            INSERT INTO `tempo_team` (`original_team_id`, `original_assessment_id`, `name`, `sponsor_id`, `company_id`, `coach_id`, `created_at`, `updated_at`)
            SELECT `team`.`id`, `assessment`.`id`,
                CONCAT(`team`.`name`, COALESCE(CONCAT(' ', `assessment`.`name`) ,'')), `team`.`sponsor_id`, `team`.`company_id`, `team`.`coach_id`,
                COALESCE(`assessment`.`created_at`, `team`.`created_at`),
                COALESCE(`assessment`.`updated_at`, `team`.`updated_at`)
            FROM
                `team`
                LEFT JOIN `assessment` ON `assessment`.`team_id` = `team`.`id`");

        $this->execute("
            INSERT INTO `tempo_team_member` (`original_team_id`, `original_assessment_id`, `team_id`, `person_id`, `active`, `created_at`, `updated_at`)
            SELECT `team`.`id`, `assessment`.`id`, `tempo_team`.`id`, `team_member`.`person_id`, `team_member`.`active`, 
                `team_member`.`created_at`, `team_member`.`updated_at`
            FROM
                `team_member`
                INNER JOIN `team` ON `team`.`id` = `team_member`.`team_id`
                LEFT JOIN `assessment` ON `assessment`.`team_id` = `team`.`id`
                LEFT JOIN `tempo_team` ON `tempo_team`.`original_team_id` = `team`.`id` AND
                    (`tempo_team`.`original_assessment_id` = `assessment`.`id` OR `assessment`.`id` IS null)");

        $this->execute("
            INSERT INTO `tempo_team_coach` (`team_id`, `coach_id`, `anonimize`, `created_at`, `updated_at`)
            SELECT `tempo_team`.`id`, `assessment_coach`.`coach_id`, 0, `tempo_team`.`created_at`, `tempo_team`.`updated_at`
            FROM 
                `assessment_coach`
                INNER JOIN `tempo_team` ON `tempo_team`.`original_assessment_id` = `assessment_coach`.`assessment_id`
            WHERE
                `assessment_coach`.`coach_id` <> `tempo_team`.`coach_id`;");

        $this->addColumn('wheel', 'team_id', $this->integer());

        $this->execute('UPDATE `wheel` 
            INNER JOIN `tempo_team` on `tempo_team`.`original_assessment_id` = `wheel`.`assessment_id`
            SET `wheel`.`team_id` = `tempo_team`.`id`');

        $this->addColumn('report', 'team_id', $this->integer());

        $this->execute('UPDATE `report` 
            INNER JOIN `tempo_team` on `tempo_team`.`original_assessment_id` = `report`.`assessment_id`
            SET `report`.`team_id` = `tempo_team`.`id`');

        $this->addColumn('stock', 'team_id', $this->integer());

        $this->execute('UPDATE `stock` 
            INNER JOIN `tempo_team` on `tempo_team`.`original_assessment_id` = `stock`.`assessment_id`
            SET `stock`.`team_id` = `tempo_team`.`id`');

        $this->dropTable('assessment_coach');

        $this->dropForeignKey('fk_wheel_assessment', 'wheel');
        $this->dropColumn('wheel', 'assessment_id');

        $this->dropForeignKey('fk_report_assessment', 'report');
        $this->dropColumn('report', 'assessment_id');

        $this->dropForeignKey('fk_stock_assessment', 'stock');
        $this->dropColumn('stock', 'assessment_id');

        $this->dropTable('assessment');
        $this->dropTable('team_member');
        $this->dropTable('team');

        $this->renameTable('tempo_team_member', 'team_member');
        $this->renameTable('tempo_team_coach', 'team_coach');
        $this->renameTable('tempo_team', 'team');
        
        $this->dropColumn('team', 'original_team_id');
        $this->dropColumn('team', 'original_assessment_id');
        $this->dropColumn('team_member', 'original_team_id');
        $this->dropColumn('team_member', 'original_assessment_id');

        $this->addForeignKey('fk_report_team', 'report', 'team_id', 'team', 'id');
        $this->addForeignKey('fk_wheel_team', 'wheel', 'team_id', 'team', 'id');
        $this->addForeignKey('fk_stock_team', 'stock', 'team_id', 'team', 'id');
    }

    public function safeDown()
    {
        throw new Exception('This migration cannot be reverted.');
    }

}
