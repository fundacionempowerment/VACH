<?php

use yii\db\Schema;
use yii\db\Migration;

class m150606_194443_add_company extends Migration {

    public function up() {
        $this->addColumn('{{%user}}', 'is_company', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0');
        $this->addColumn('{{%user}}', 'company_id', Schema::TYPE_INTEGER . ' NULL');

        $this->addForeignKey('fk_user_company', '{{%user}}', 'company_id', '{{%user}}', 'id');
    }

    public function down() {
        $this->dropForeignKey('fk_user_company', '{{%user}}');

        $this->dropColumn('{{%user}}', 'company_id');
        $this->dropColumn('{{%user}}', 'is_company');
    }

}
