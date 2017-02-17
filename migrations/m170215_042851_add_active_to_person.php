<?php

use yii\db\Migration;

class m170215_042851_add_active_to_person extends Migration
{

    public function up()
    {
        $this->addColumn('{{%team_member}}', 'active', $this->boolean()->notNull()->defaultValue(true));
    }

    public function down()
    {
        $this->dropColumn('{{%team_member}}', 'active', $this->boolean()->notNull()->defaultValue(true));
    }

}
