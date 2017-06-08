<?php

use yii\db\Migration;

class m170520_185059_add_missing_fk extends Migration
{

    public function up()
    {
        $this->addForeignKey('fk_feedback_user', 'feedback', 'user_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_feedback_user', 'feedback');
    }

}
