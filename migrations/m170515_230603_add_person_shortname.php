<?php

use yii\db\Migration;

class m170515_230603_add_person_shortname extends Migration
{

    public function up()
    {
        $this->addColumn('person', 'shortname', $this->string()->null());
        
        $this->execute("UPDATE `person` SET `shortname` = CONCAT(SUBSTRING_INDEX(`name`, ' ', 1) , ' ' , LEFT(`person`.`surname`, 1))");
    }

    public function down()
    {
        $this->dropColumn('person', 'shortname');
    }

}
