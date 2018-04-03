<?php

use yii\db\Migration;

/**
 * Class m180327_234428_add_unique_username
 */
class m180327_234428_add_unique_username extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('uq_user_username', 'user', 'username', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('uq_user_username', 'user');
    }


}
