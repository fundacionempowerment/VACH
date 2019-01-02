<?php

use yii\db\Migration;

/**
 * Class m180929_200905_drop_init_transaction_status
 */
class m180929_200905_drop_init_transaction_status extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("UPDATE `transaction` SET `status` = 'pending' where `status` = 'init'");
        $this->execute("UPDATE `transaction_log` SET `status` = 'pending' where `status` = 'init'");

        $this->execute("ALTER TABLE `transaction_log`"
            . "CHANGE `status` `status` ENUM('pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

        $this->execute("ALTER TABLE `transaction_log`"
            . "CHANGE `status` `status` ENUM('pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->execute("ALTER TABLE `transaction_log`"
            . "CHANGE `status` `status` ENUM('init','pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

        $this->execute("ALTER TABLE `transaction_log`"
            . "CHANGE `status` `status` ENUM('init','pending','paid','rejected','error') "
            . "NOT NULL DEFAULT 'pending';");

    }

}
