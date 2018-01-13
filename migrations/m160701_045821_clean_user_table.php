<?php

use yii\db\Migration;

class m160701_045821_clean_user_table extends Migration {

    public function safeUp() {
        $this->execute('DELETE FROM `user` WHERE `user`.`is_coach` = 0');

        $this->dropForeignKey('fk_user_coach', '{{%user}}');
        $this->dropForeignKey('fk_user_company', '{{%user}}');

        $this->dropColumn('{{%user}}', 'is_coach');
        $this->dropColumn('{{%user}}', 'is_company');
        $this->dropColumn('{{%user}}', 'coach_id');
        $this->dropColumn('{{%user}}', 'gender');
        $this->dropColumn('{{%user}}', 'company_id');
    }

    public function safeDown() {
        throw new Exception("This migration cannot be reverted.");
    }

}
