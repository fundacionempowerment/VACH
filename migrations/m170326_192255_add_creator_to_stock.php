<?php

use yii\db\Migration;

class m170326_192255_add_creator_to_stock extends Migration
{

    public function up()
    {
        $this->addColumn('stock', 'creator_id', $this->integer()->null());
        $this->execute('UPDATE `stock` SET `creator_id` = `coach_id`');
        $this->alterColumn('stock', 'creator_id', $this->integer()->notNull());

        $this->addColumn('payment', 'creator_id', $this->integer()->null());
        $this->execute('UPDATE `payment` SET `creator_id` = `coach_id`');
        $this->alterColumn('payment', 'creator_id', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('stock', 'creator_id');
        $this->dropColumn('payment', 'creator_id');
    }

}
