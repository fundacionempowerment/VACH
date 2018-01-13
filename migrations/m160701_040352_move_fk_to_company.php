<?php

use yii\db\Migration;

class m160701_040352_move_fk_to_company extends Migration {

    public function safeUp() {
        $this->dropForeignKey('fk_team_company', '{{%team}}');
        $this->addForeignKey('fk_team_company', '{{%team}}', 'company_id', '{{%company}}', 'id');
    }

    public function safeDown() {
        $this->dropForeignKey('fk_team_company', '{{%team}}');
        $this->addForeignKey('fk_team_company', '{{%team}}', 'company_id', '{{%user}}', 'id');
    }

}
