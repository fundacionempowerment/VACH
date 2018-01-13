<?php

use yii\db\Migration;

class m170722_210647_fix_stock_status extends Migration
{

    public function safeUp()
    {
        $this->db->createCommand("UPDATE `stock` SET `status` = 'consumed' WHERE `consumed_stamp` IS NOT NULL")
                ->execute();
    }

    public function safeDown()
    {
        
    }

}
