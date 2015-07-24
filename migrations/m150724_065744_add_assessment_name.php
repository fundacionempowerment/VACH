<?php

use yii\db\Schema;
use yii\db\Migration;

class m150724_065744_add_assessment_name extends Migration {

    public function up() {
        $this->addColumn('{{%assessment}}', 'name', Schema::TYPE_STRING);
        $this->execute('update {{%assessment}} set name = concat(\'#\' , id)');
    }

    public function down() {
        $this->dropColumn('{{%assessment}}', 'name');
    }

}
