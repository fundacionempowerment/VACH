<?php

use yii\db\Schema;
use yii\db\Migration;

class m150617_015144_add_dimension_to_answers extends Migration {

    public function up() {
        $this->addColumn('{{%wheel_answer}}', 'dimension', Schema::TYPE_INTEGER);
        $this->execute('update {{%wheel_answer}} set dimension = answer_order div 10');
    }

    public function down() {
        $this->dropColumn('{{%wheel_answer}}', 'dimension');
    }

}
