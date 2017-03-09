<?php

use yii\db\Migration;

class m170309_044329_null_in_reports extends Migration
{

    public function up()
    {
        $this->alterColumn('report', 'introduction', $this->text()->null());
        $this->alterColumn('report', 'effectiveness', $this->text()->null());
        $this->alterColumn('report', 'performance', $this->text()->null());
        $this->alterColumn('report', 'competences', $this->text()->null());
        $this->alterColumn('report', 'emergents', $this->text()->null());
        $this->alterColumn('report', 'summary', $this->text()->null());
        $this->alterColumn('report', 'action_plan', $this->text()->null());

        $this->alterColumn('individual_report', 'perception', $this->text()->null());
        $this->alterColumn('individual_report', 'relations', $this->text()->null());
        $this->alterColumn('individual_report', 'competences', $this->text()->null());
        $this->alterColumn('individual_report', 'emergents', $this->text()->null());
        $this->alterColumn('individual_report', 'performance', $this->text()->null());
        $this->alterColumn('individual_report', 'summary', $this->text()->null());
    }

    public function down()
    {
        $this->alterColumn('report', 'introduction', $this->text()->notNull());
        $this->alterColumn('report', 'effectiveness', $this->text()->notNull());
        $this->alterColumn('report', 'performance', $this->text()->notNull());
        $this->alterColumn('report', 'competences', $this->text()->notNull());
        $this->alterColumn('report', 'emergents', $this->text()->notNull());
        $this->alterColumn('report', 'summary', $this->text()->notNull());
        $this->alterColumn('report', 'action_plan', $this->text()->notNull());

        $this->alterColumn('individual_report', 'perception', $this->text()->notNull());
        $this->alterColumn('individual_report', 'relations', $this->text()->notNull());
        $this->alterColumn('individual_report', 'competences', $this->text()->notNull());
        $this->alterColumn('individual_report', 'emergents', $this->text()->notNull());
        $this->alterColumn('individual_report', 'performance', $this->text()->notNull());
        $this->alterColumn('individual_report', 'summary', $this->text()->notNull());
    }

}
