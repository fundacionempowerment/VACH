<?php

use yii\db\Migration;

/**
 * Class m180826_233158_drop_init_payment_status
 */
class m180826_233158_drop_init_payment_status extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("UPDATE `payment` SET `status` = 'pending' where `status` = 'init'");
        $this->execute("UPDATE `payment_log` SET `status` = 'pending' where `status` = 'init'");

        $this->execute("ALTER TABLE `payment_log`"
            . "CHANGE `status` `status` ENUM('pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

        $this->execute("ALTER TABLE `payment_log`"
            . "CHANGE `status` `status` ENUM('pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->execute("ALTER TABLE `payment_log`"
            . "CHANGE `status` `status` ENUM('init','pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

        $this->execute("ALTER TABLE `payment_log`"
            . "CHANGE `status` `status` ENUM('init','pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

    }

}
