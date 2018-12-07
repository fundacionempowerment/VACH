<?php

use yii\db\Migration;

/**
 * Class m181201_170004_add_team_type_dimension_sort
 */
class m181201_170004_add_team_type_dimension_sort extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('team_type', 'dimension_sort', $this->integer()->notNull()->defaultValue(SORT_ASC));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('team_type', 'dimension_sort');
    }

}
