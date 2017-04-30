<?php

use yii\db\Migration;

class m170429_223113_add_manual_payment_field extends Migration
{

    public function up()
    {
        $this->addColumn('payment', 'is_manual', $this->boolean()->notNull()->defaultValue(0));

        $this->execute("UPDATE `payment` SET `is_manual`= 1 WHERE `external_id` IS NULL AND `status` = 'paid'");
    }

    public function down()
    {
        $this->dropColumn('payment', 'is_manual');
    }

}
