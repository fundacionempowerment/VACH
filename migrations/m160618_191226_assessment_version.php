<?php

use yii\db\Migration;

class m160618_191226_assessment_version extends Migration {

    public function up() {
        $this->addColumn('{{%assessment}}', 'version', $this->integer()->defaultValue(1)->notNull());
    }

    public function down() {
        $this->dropColumn('{{%assessment}}', 'version');

        return true;
    }

}
