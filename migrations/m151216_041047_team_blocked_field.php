<?php

use yii\db\Schema;
use yii\db\Migration;

class m151216_041047_team_blocked_field extends Migration {

    public function up() {
        $this->addColumn('{{%team}}', 'blocked', $this->boolean() . ' NOT NULL DEFAULT 0');

        $this->execute('update {{%team}} '
                . 'set blocked = 1 '
                . 'where id in ('
                . 'select team_id from assessment'
                . ')');
    }

    public function down() {
        $this->dropColumn('{{%team}}', 'blocked');
    }

}
