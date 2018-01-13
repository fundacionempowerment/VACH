<?php

use yii\db\Migration;

class m170719_025000_new_stock_table extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tempo_stock}}', [
            'id' => $this->primaryKey(),
            'coach_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'status' => "enum('invalid','valid','consumed','error') NOT NULL DEFAULT 'invalid'",
            'created_stamp' => $this->dateTime()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'payment_id' => $this->integer()->null(),
            'consumed_stamp' => $this->dateTime()->null(),
            'consumer_id' => $this->integer()->null(),
            'team_id' => $this->integer()->null(),
                ], $tableOptions);

        $purchases = $this->db->createCommand('SELECT '
                        . 'ABS(`stock`.`quantity`) as `quantity`, `stock`.`coach_id`, `stock`.`product_id`, `stock`.`price`, `stock`.`status`, `payment`.`id` as `payment_id`, `payment`.`stamp` as `payment_stamp`, `payment`.`creator_id` '
                        . 'FROM `payment` '
                        . 'INNER JOIN `stock` ON `stock`.`id` = `payment`.`stock_id` '
                        . 'ORDER BY `payment`.`id`, `stock`.`id` ')->queryAll();

        foreach ($purchases as $purchase) {
            for ($i = 1; $i <= $purchase['quantity']; $i++) {
                $this->insert('tempo_stock', [
                    'coach_id' => $purchase['coach_id'],
                    'product_id' => $purchase['product_id'],
                    'price' => $purchase['price'],
                    'status' => $purchase['status'],
                    'created_stamp' => $purchase['payment_stamp'],
                    'creator_id' => $purchase['creator_id'],
                    'payment_id' => $purchase['payment_id'],
                ]);
            }
        }

        $consumptions = $this->db->createCommand('SELECT * FROM `stock` WHERE `quantity` < 0 ORDER BY `id` ASC')->queryAll();

        foreach ($consumptions as $consumption) {
            $quantity = abs($consumption['quantity']);

            for ($i = 1; $i <= $quantity; $i++) {
                $available_stock_id = $this->db->createCommand('SELECT `id` FROM `tempo_stock` '
                                . 'WHERE `consumed_stamp` is null '
                                . 'AND `coach_id` = ' . $consumption['coach_id']
                                . ' ORDER BY `id` ASC LIMIT 1')->queryScalar();

                $this->update('tempo_stock', [
                    'consumed_stamp' => $consumption['stamp'],
                    'consumer_id' => $consumption['creator_id'],
                    'team_id' => $consumption['team_id'],
                        ], [
                    'id' => $available_stock_id,
                ]);
            }
        }

        $this->dropForeignKey('fk_payment_stock', 'payment');
        $this->dropColumn('payment', 'stock_id');

        $this->dropForeignKey('fk_stock_coach', 'stock');
        $this->dropForeignKey('fk_stock_creator', 'stock');
        $this->dropForeignKey('fk_stock_product', 'stock');
        $this->dropForeignKey('fk_stock_team', 'stock');

        $this->dropTable('stock');

        $this->renameTable('tempo_stock', 'stock');

        $this->addForeignKey('fk_stock_coach', 'stock', 'coach_id', 'user', 'id');
        $this->addForeignKey('fk_stock_creator', 'stock', 'creator_id', 'user', 'id');
        $this->addForeignKey('fk_stock_consumer', 'stock', 'consumer_id', 'user', 'id');
        $this->addForeignKey('fk_stock_team', 'stock', 'team_id', 'team', 'id');
        $this->addForeignKey('fk_stock_product', 'stock', 'product_id', 'product', 'id');
        $this->addForeignKey('fk_stock_payment', 'stock', 'payment_id', 'payment', 'id');
    }

    public function down()
    {
        throw new Exception('This migration cannot be reverted.');
    }

}
