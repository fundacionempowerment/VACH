<?php

use yii\db\Schema;
use yii\db\Migration;

class m150723_223230_add_user_to_feedback extends Migration {

    public function up() {
        $this->addColumn('{{%feedback}}', 'user_id', Schema::TYPE_INTEGER);
    }

    public function down() {
        $this->dropColumn('{{%feedback}}', 'user_id');
    }

}
