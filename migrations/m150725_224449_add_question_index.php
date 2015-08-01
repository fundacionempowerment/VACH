<?php

use yii\db\Schema;
use yii\db\Migration;

class m150725_224449_add_question_index extends Migration {

    public function up() {
        $this->createIndex('index_order', '{{%wheel_question}}', 'order, type');
    }

    public function down() {
        $this->dropIndex('index_order', '{{%wheel_question}}');
    }

}
