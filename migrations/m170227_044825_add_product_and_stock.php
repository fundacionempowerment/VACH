<?php

use yii\db\Migration;

class m170227_044825_add_product_and_stock extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->null(),
            'price' => $this->decimal(10, 2)->notNull()->comment('USD'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);

        $this->insert('{{%product}}', [
            'name' => 'Team Assessment Licence',
            'description' => 'Team Assessment Licence',
            'price' => 18,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->createTable('{{%stock}}', [
            'id' => $this->primaryKey(),
            'coach_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'total' => $this->decimal(10, 2)->notNull(),
            'status' => "enum('pending','paid','gifted','discarded','error') NOT NULL DEFAULT 'pending'",
            'stamp' => $this->dateTime()->notNull(),
                ], $tableOptions);

        $this->addForeignKey('fk_stock_coach', '{{%stock}}', 'coach_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_stock_product', '{{%stock}}', 'product_id', '{{%product}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_stock_coach', '{{%stock}}');
        $this->dropForeignKey('fk_stock_product', '{{%stock}}');
        $this->dropForeignKey('fk_stock_payment', '{{%stock}}');
        $this->dropTable('{{%stock}}');
        $this->dropTable('{{%product}}');
    }

}
