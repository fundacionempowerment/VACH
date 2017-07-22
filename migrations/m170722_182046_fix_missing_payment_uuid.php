<?php

use yii\db\Migration;

class m170722_182046_fix_missing_payment_uuid extends Migration
{

    public function safeUp()
    {
        $rows = $this->db->createCommand("SELECT id FROM `payment` WHERE `uuid` is null or `uuid` = ''")->queryAll();

        foreach ($rows as $row) {
            $this->update('payment', [
                'uuid' => uniqid('', true),
                    ], [
                'id' => $row['id'],
            ]);
        }
    }

    public function safeDown()
    {
        
    }

}
