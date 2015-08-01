<?php

use yii\db\Schema;
use yii\db\Migration;

class m150609_001157_add_user_phone extends Migration {

    public function up() {
        $this->addColumn('{{%user}}', 'phone', Schema::TYPE_STRING . ' NULL');
    }

    public function down() {
        $this->dropColumn('{{%user}}', 'phone');
    }

}
