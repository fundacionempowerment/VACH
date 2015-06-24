<?php

use yii\db\Migration;

class m150528_041349_change_wheel_user_to_coachee extends Migration {

    public function up() {
        $this->dropForeignKey('fk_wheel_user', '{{%wheel}}');
        $this->renameColumn('{{%wheel}}', 'user_id', 'coachee_id');
        $this->addForeignKey('fk_wheel_user', '{{%wheel}}', 'coachee_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down() {
        $this->dropForeignKey('fk_wheel_user', '{{%wheel}}');
        $this->renameColumn('{{%wheel}}', 'coachee_id', 'user_id');
        $this->addForeignKey('fk_wheel_user', '{{%wheel}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

}
