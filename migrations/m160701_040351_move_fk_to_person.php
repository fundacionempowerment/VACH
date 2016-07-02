<?php

use yii\db\Migration;

class m160701_040351_move_fk_to_person extends Migration {

    public function safeUp() {
        $this->dropForeignKey('fk_team_member_user', '{{%team_member}}');
        $this->renameColumn('{{%team_member}}', 'user_id', 'person_id');
        $this->addForeignKey('fk_team_member_person', '{{%team_member}}', 'person_id', '{{%person}}', 'id', 'CASCADE');

        $this->dropForeignKey('fk_team_sponsor', '{{%team}}');
        $this->addForeignKey('fk_team_sponsor', '{{%team}}', 'sponsor_id', '{{%person}}', 'id');

        $this->dropForeignKey('fk_individual_report_user', '{{%individual_report}}');
        $this->renameColumn('{{%individual_report}}', 'user_id', 'person_id');
        $this->addForeignKey('fk_individual_report_user', '{{%individual_report}}', 'person_id', '{{%person}}', 'id', 'CASCADE');

        $this->dropForeignKey('fk_wheel_observed', '{{%wheel}}');
        $this->addForeignKey('fk_wheel_observed', '{{%wheel}}', 'observed_id', '{{%person}}', 'id');

        $this->dropForeignKey('fk_wheel_observer', '{{%wheel}}');
        $this->addForeignKey('fk_wheel_observer', '{{%wheel}}', 'observer_id', '{{%person}}', 'id');
    }

    public function safeDown() {
        $this->dropForeignKey('fk_wheel_observed', '{{%wheel}}');
        $this->addForeignKey('fk_wheel_observed', '{{%wheel}}', 'observed_id', '{{%user}}', 'id');

        $this->dropForeignKey('fk_wheel_observer', '{{%wheel}}');
        $this->addForeignKey('fk_wheel_observer', '{{%wheel}}', 'observer_id', '{{%user}}', 'id');

        $this->dropForeignKey('fk_individual_report_user', '{{%individual_report}}');
        $this->renameColumn('{{%individual_report}}', 'person_id', 'user_id');
        $this->addForeignKey('fk_individual_report_user', '{{%individual_report}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->dropForeignKey('fk_team_sponsor', '{{%team}}');
        $this->addForeignKey('fk_team_sponsor', '{{%team}}', 'sponsor_id', '{{%user}}', 'id');

        $this->dropForeignKey('fk_team_member_person', '{{%team_member}}');
        $this->renameColumn('{{%team_member}}', 'person_id', 'user_id');
        $this->addForeignKey('fk_team_member_user', '{{%team_member}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

}
