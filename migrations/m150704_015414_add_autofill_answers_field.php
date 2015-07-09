<?php

use yii\db\Schema;
use yii\db\Migration;

class m150704_015414_add_autofill_answers_field extends Migration {

    public function up() {
        $this->addColumn('{{%assessment}}', 'autofill_answers', Schema::TYPE_BOOLEAN);
    }

    public function down() {
        $this->dropColumn('{{%assessment}}', 'autofill_answers');
    }

}
