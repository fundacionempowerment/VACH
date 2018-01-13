<?php

use yii\db\Migration;

class m160614_041645_person_gender extends Migration {

    public function up() {
        $this->addColumn('{{%user}}', 'gender', $this->integer()->defaultValue(0)->notNull());
    }

    public function down() {
        $this->dropColumn('{{%user}}', 'gender');

        return true;
    }

}
