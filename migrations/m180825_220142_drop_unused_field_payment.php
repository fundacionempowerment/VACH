<?php

use yii\db\Migration;

/**
 * Class m180825_220142_drop_unused_field_payment
 */
class m180825_220142_drop_unused_field_payment extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropColumn('payment', 'external_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->addColumn('payment', 'external_id', $this->string());
    }

}
