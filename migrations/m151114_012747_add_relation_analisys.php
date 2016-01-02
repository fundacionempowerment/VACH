<?php

use yii\db\Schema;
use yii\db\Migration;

class m151114_012747_add_relation_analisys extends Migration {

    public function up() {
        $this->addColumn('{{%report}}', 'relations', Schema::TYPE_TEXT . ' NULL');
    }

    public function down() {
        $this->dropColumn('{{%report}}', 'relations');
    }

}
