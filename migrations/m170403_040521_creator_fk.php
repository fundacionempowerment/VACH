<?php

use yii\db\Migration;

class m170403_040521_creator_fk extends Migration
{

    public function up()
    {
        $this->addForeignKey('fk_stock_creator', 'stock', 'creator_id', 'user', 'id');
        $this->addForeignKey('fk_payment_creator', 'payment', 'creator_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_stock_creator', 'stock');
        $this->dropForeignKey('fk_payment_creator', 'payment');
    }

}
