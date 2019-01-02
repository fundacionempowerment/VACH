<?php

use yii\db\Migration;

/**
 * Class m181018_004525_add_notes
 */
class m181018_004525_add_notes extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('company', 'notes', $this->string(1000)->null());
        $this->addColumn('person', 'notes', $this->string(1000)->null());
        $this->addColumn('team', 'notes', $this->string(1000)->null());
        $this->addColumn('user', 'notes', $this->string(1000)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('company', 'notes');
        $this->dropColumn('person', 'notes');
        $this->dropColumn('team', 'notes');
        $this->dropColumn('user', 'notes');
    }
}
