<?php

use yii\db\Migration;

/**
 * Class m210731_175938_team_type_enabled_field
 */
class m210731_175938_team_type_enabled_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('team_type', 'enabled',
            $this->boolean()->notNull()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('team_type', 'enabled');
    }
}
