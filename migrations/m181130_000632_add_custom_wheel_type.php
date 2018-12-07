<?php

use yii\db\Migration;

/**
 * Class m181130_000632_add_custom_wheel_type
 */
class m181130_000632_add_custom_wheel_type extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('team_type', 'level_0_name',
            $this->string(200)->notNull()->defaultValue("individual"));
        $this->addColumn('team_type', 'level_0_enabled',
            $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn('team_type', 'level_1_name',
            $this->string(200)->notNull()->defaultValue("grupal"));
        $this->addColumn('team_type', 'level_1_enabled',
            $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn('team_type', 'level_2_name',
            $this->string(200)->notNull()->defaultValue("organizacional"));
        $this->addColumn('team_type', 'level_2_enabled',
            $this->boolean()->notNull()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('team_type', 'level_0_name');
        $this->dropColumn('team_type', 'level_0_enabled');
        $this->dropColumn('team_type', 'level_1_name');
        $this->dropColumn('team_type', 'level_1_enabled');
        $this->dropColumn('team_type', 'level_2_name');
        $this->dropColumn('team_type', 'level_2_enabled');
    }

}
