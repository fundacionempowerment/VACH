<?php

use yii\db\Migration;

class m170424_034317_wheel_status_and_sent_count extends Migration
{

    public function up()
    {
        $this->addColumn('wheel', 'sent_count', $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn('wheel', 'status', "ENUM('created', 'sent', 'received', 'in_progress', 'done') DEFAULT 'created' NOT NULL");
        
        $this->createTable('tempo', [
            'wheel_id' => $this->integer()->notNull(),
            'wheel_type' => $this->integer()->notNull(),            
            'answer_count' => $this->integer()->notNull()->defaultValue(0),
        ]);
        
        $this->execute('INSERT INTO `tempo` (`wheel_id`, `wheel_type`, `answer_count`)
            SELECT `wheel`.`id`, `wheel`.`type`, count(`wheel_answer`.`id`)
            FROM `wheel` 
                INNER JOIN `wheel_answer`
                    ON `wheel_answer`.`wheel_id` = `wheel`.`id`
            GROUP BY `wheel`.`id`, `wheel`.`type`');
        
        $this->execute("UPDATE `wheel` SET `status` = 'in_progress' WHERE `wheel`.`id` IN
            (SELECT `tempo`.`wheel_id`
            FROM `tempo` 
            WHERE 
                (`tempo`.`wheel_type` = 0 AND `tempo`.`answer_count` < 80)
                    OR (`tempo`.`wheel_type` <> 0 AND `tempo`.`answer_count` < 64)
            )");
        
        $this->execute("UPDATE `wheel` SET `status` = 'done' WHERE `wheel`.`id` IN
            (SELECT `tempo`.`wheel_id`
            FROM `tempo` 
            WHERE 
                (`tempo`.`wheel_type` = 0 AND `tempo`.`answer_count` = 80)
                    OR (`tempo`.`wheel_type` <> 0 AND `tempo`.`answer_count` = 64)
            )");

        $this->dropTable('tempo');
    }

    public function down()
    {
        $this->dropColumn('wheel', 'sent_count');
        $this->dropColumn('wheel', 'status');
    }

}
