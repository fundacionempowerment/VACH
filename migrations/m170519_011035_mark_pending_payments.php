<?php

use yii\db\Migration;

class m170519_011035_mark_pending_payments extends Migration
{

    public function up()
    {
        $this->update('payment', ['status' => 'pending'], ['is_manual' => true]);
    }

    public function down()
    {
        
    }

}
