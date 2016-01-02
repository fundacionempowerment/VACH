<?php

use yii\db\Schema;
use yii\db\Migration;

class m151114_013636_add_individual_performance_analisys extends Migration {

    public function up() {
        $this->addColumn('{{%individual_report}}', 'performance', Schema::TYPE_TEXT);
    }

    public function down() {
        $this->dropColumn('{{%individual_report}}', 'performance');
    }

}
