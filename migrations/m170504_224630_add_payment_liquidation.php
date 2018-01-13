<?php

use yii\db\Migration;

class m170504_224630_add_payment_liquidation extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('liquidation', [
            'id' => $this->primaryKey(),
            'stamp' => $this->string()->notNull(),
            'currency' => $this->string()->notNull(),
            'raw_amount' => $this->decimal(10, 4)->notNull(),
            'commision' => $this->decimal(10, 4)->notNull(),
            'net_amount' => $this->decimal(10, 4)->notNull(),
            'part1_amount' => $this->decimal(10, 4)->notNull(),
            'part2_amount' => $this->decimal(10, 4)->notNull(),
                ], $tableOptions);

        $this->addColumn('payment', 'part_distribution', $this->integer()->notNull()->defaultValue(50));
        $this->addColumn('payment', 'liquidation_id', $this->integer()->null());

        $this->addForeignKey('fk_payment_liquidation', 'payment', 'liquidation_id', 'liquidation', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_payment_liquidation', 'payment');
        $this->dropColumn('payment', 'liquidation_id');
        $this->dropColumn('payment', 'part_distribution');
        $this->dropTable('liquidation');
    }

}
