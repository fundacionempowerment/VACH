<?php

use yii\db\Migration;

class m170427_042955_add_declined_payment_status extends Migration
{

    public function up()
    {
        $this->execute("ALTER TABLE `payment`"
                . "CHANGE `status` `status` ENUM('init','pending','paid','rejected','error') "
                . "NOT NULL DEFAULT 'init';");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `payment`"
                . "CHANGE `status` `status` ENUM('init','pending','paid','partial','error') "
                . "NOT NULL DEFAULT 'init';");
    }

}
