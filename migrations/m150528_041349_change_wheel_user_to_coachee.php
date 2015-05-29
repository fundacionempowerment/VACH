<?php

use yii\db\Migration;

class m150528_041349_change_wheel_user_to_coachee extends Migration {

    public function up() {
        $this->renameColumn('{{%wheel}}', 'user_id', 'coachee_id');
    }

    public function down() {
        $this->renameColumn('{{%wheel}}', 'coachee_id', 'user_id');
    }

}
