<?php

use yii\db\Migration;

class m160701_034039_fill_company extends Migration {

    public function up() {
        $this->execute(
                "insert into company
            (
                `company`.`coach_id`,
                `company`.`created_at`,
                `company`.`email`,
                `company`.`id`,
                `company`.`name`,
                `company`.`phone`,
                `company`.`updated_at`
            ) select 
                coalesce(`user`.`coach_id`, 1),
                `user`.`created_at`,
                `user`.`email`,
                `user`.`id`,
                `user`.`name`,
                coalesce(`user`.`phone`, ''),
                `user`.`updated_at`    
            from
                `user`
            where
                `user`.`is_company` = 1
            "
        );
    }

    public function down() {
        $this->execute("truncate company");
    }

}
