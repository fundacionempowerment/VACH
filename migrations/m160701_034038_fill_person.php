<?php

use yii\db\Migration;

class m160701_034038_fill_person extends Migration {

    public function up() {
        $this->execute(
                "insert into person
            (
                `person`.`coach_id`,
                `person`.`created_at`,
                `person`.`email`,
                `person`.`gender`,
                `person`.`id`,
                `person`.`name`,
                `person`.`surname`,
                `person`.`phone`,
                `person`.`updated_at`
            ) select 
                coalesce(`user`.`coach_id`, 1),
                `user`.`created_at`,
                `user`.`email`,
                `user`.`gender`,
                `user`.`id`,
                `user`.`name`,
                `user`.`surname`,
                coalesce(`user`.`phone`, ''),
                `user`.`updated_at`    
            from
                `user`
            where
                `user`.`is_coach` = 0
                or `user`.`id` in (select team_member.user_id from team_member)
                or `user`.`id` in (select individual_report.user_id from individual_report)
                or `user`.`id` in (select team.sponsor_id from team)
                or `user`.`id` in (select wheel.observed_id from wheel)
                or `user`.`id` in (select wheel.observer_id from wheel)
            "
        );
    }

    public function down() {
        $this->execute("truncate person");
    }

}
