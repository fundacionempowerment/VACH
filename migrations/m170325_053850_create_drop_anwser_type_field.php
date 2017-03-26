<?php

use yii\db\Migration;

class m170325_053850_create_drop_anwser_type_field extends Migration
{

    public function up()
    {
        $this->dropColumn('wheel_question', 'answer_type');
    }

    public function down()
    {
        $this->dropColumn('wheel_question', 'answer_type', $this->integer()->notNull()->defaultValue(1));
    }

}
