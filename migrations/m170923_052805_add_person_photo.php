<?php

use yii\db\Migration;

class m170923_052805_add_person_photo extends Migration
{

    public function safeUp()
    {
        $this->addColumn('person', 'photo', $this->string()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('person', 'photo');
    }

}
