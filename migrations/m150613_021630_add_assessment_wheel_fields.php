<?php

use yii\db\Schema;
use yii\db\Migration;

class m150613_021630_add_assessment_wheel_fields extends Migration {

    public function up() {
        $this->addColumn('{{%assessment}}', 'individual_status', Schema::TYPE_INTEGER. ' NOT NULL');
        $this->addColumn('{{%assessment}}', 'group_status', Schema::TYPE_INTEGER. ' NOT NULL');
        $this->addColumn('{{%assessment}}', 'organizational_status', Schema::TYPE_INTEGER. ' NOT NULL');

        $this->addColumn('{{%wheel}}', 'assessment_id', Schema::TYPE_INTEGER. ' NOT NULL');
        $this->addColumn('{{%wheel}}', 'type', Schema::TYPE_INTEGER. ' NOT NULL');
        $this->addColumn('{{%wheel}}', 'token', Schema::TYPE_STRING. ' NOT NULL');

        $this->addForeignKey('fk_wheel_assessment', '{{%wheel}}', 'assessment_id', '{{%assessment}}', 'id');

        $this->dropForeignKey('fk_wheel_user', '{{%wheel}}');
        $this->renameColumn('{{%wheel}}', 'coachee_id', 'observer_id');
        $this->addForeignKey('fk_wheel_observer', '{{%wheel}}', 'observer_id', '{{%user}}', 'id');
        $this->addColumn('{{%wheel}}', 'observed_id', Schema::TYPE_INTEGER);
        $this->addForeignKey('fk_wheel_observed', '{{%wheel}}', 'observed_id', '{{%user}}', 'id');

        $this->execute('update wheel set observed_id = observer_id');
    }

    public function down() {
        $this->dropForeignKey('fk_wheel_observed', '{{%wheel}}');
        $this->dropColumn('{{%wheel}}', 'observed_id');
        $this->dropForeignKey('fk_wheel_observer', '{{%wheel}}');
        $this->renameColumn('{{%wheel}}', 'observer_id', 'coachee_id');
        $this->addForeignKey('fk_wheel_user', '{{%wheel}}', 'coachee_id', '{{%user}}', 'id', 'CASCADE');

        $this->dropForeignKey('fk_wheel_assessment', '{%wheel}');

        $this->dropColumn('{{%wheel}}', 'assessment_id');
        $this->dropColumn('{{%wheel}}', 'type');
        $this->dropColumn('{{%wheel}}', 'token');

        $this->dropColumn('{{%assessment}}', 'individual_status');
        $this->dropColumn('{{%assessment}}', 'group_status');
        $this->dropColumn('{{%assessment}}', 'organizational_status');
    }

}
