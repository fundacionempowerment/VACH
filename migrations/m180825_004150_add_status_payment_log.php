<?php

use yii\db\Migration;

/**
 * Class m180825_004150_add_status_payment_log
 */
class m180825_004150_add_status_payment_log extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("ALTER TABLE `payment_log`"
            . "CHANGE `status` `status` ENUM('init','pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'init';");

        $this->execute("UPDATE `payment_log` SET `status` = 'rejected' where `status` = ''");
    }

    public function down() {
        $this->execute("UPDATE `payment_log` SET `status` = '' where `status` = 'rejected'");

        $this->execute("ALTER TABLE `payment_log`"
            . "CHANGE `status` `status` ENUM('init','pending','paid','partial','error') "
            . "NOT NULL DEFAULT 'init';");
    }

}
