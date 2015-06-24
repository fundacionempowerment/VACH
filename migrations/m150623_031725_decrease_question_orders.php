<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_031725_decrease_question_orders extends Migration {

    public function up() {
        $this->execute('update {{%wheel_question}} set `order` = `order` - 1');
    }

    public function down() {
        $this->execute('update {{%wheel_question}} set `order` = `order` + 1');
    }

}
