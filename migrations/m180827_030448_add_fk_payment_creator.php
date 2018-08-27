<?php

use yii\db\Migration;

/**
 * Class m180827_030448_add_fk_payment_creator
 */
class m180827_030448_add_fk_payment_creator extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addForeignKey('fk_payment_creator', 'payment', 'creator_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('fk_payment_creator', 'payment');

    }

}
