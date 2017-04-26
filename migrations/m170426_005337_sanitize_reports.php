<?php

use yii\db\Migration;

class m170426_005337_sanitize_reports extends Migration
{

    public function up()
    {
        $this->execute("UPDATE `report` SET
            `introduction` = REPLACE(`introduction`, '<br>', '<br/>'),
            `effectiveness` = REPLACE(`effectiveness`, '<br>', '<br/>'),
            `performance` = REPLACE(`performance`, '<br>', '<br/>'),
            `competences` = REPLACE(`competences`, '<br>', '<br/>'),
            `emergents` = REPLACE(`emergents`, '<br>', '<br/>'),
            `summary` = REPLACE(`summary`, '<br>', '<br/>'),
            `action_plan` = REPLACE(`action_plan`, '<br>', '<br/>'),
            `relations` = REPLACE(`relations`, '<br>', '<br/>');");

        $this->execute("UPDATE `individual_report` SET
            `perception` = REPLACE(`perception`, '<br>', '<br/>'),
            `performance` = REPLACE(`performance`, '<br>', '<br/>'),
            `competences` = REPLACE(`competences`, '<br>', '<br/>'),
            `emergents` = REPLACE(`emergents`, '<br>', '<br/>'),
            `relations` = REPLACE(`relations`, '<br>', '<br/>');");
    }

    public function down()
    {
        
    }

}
