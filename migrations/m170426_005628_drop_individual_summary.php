<?php

use yii\db\Migration;

class m170426_005628_drop_individual_summary extends Migration
{

    public function up()
    {
        $this->dropColumn('individual_report', 'summary');
    }

    public function down()
    {
        $this->addColumn('individual_report', 'summary', $this->string());
    }

}
