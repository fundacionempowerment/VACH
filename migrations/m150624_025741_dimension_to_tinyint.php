<?php

use yii\db\Schema;
use yii\db\Migration;

class m150624_025741_dimension_to_tinyint extends Migration {

    public function up() {
        $this->alterColumn('{{%wheel_answer}}', 'dimension', Schema::TYPE_SMALLINT);
    }

    public function down() {
        $this->alterColumn('{{%wheel_answer}}', 'dimension', Schema::TYPE_INTEGER);
    }

}
