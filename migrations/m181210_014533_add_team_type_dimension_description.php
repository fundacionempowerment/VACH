<?php

use yii\db\Migration;

/**
 * Class m181210_014533_add_team_type_dimension_description
 */
class m181210_014533_add_team_type_dimension_description extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('team_type_dimension', 'description', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('team_type_dimension', 'description');
    }

}
