<?php

use yii\db\Migration;

class m170516_022644_add_report_keywords extends Migration
{

    public function up()
    {
        $this->addColumn('report', 'introduction_keywords', $this->text()->null());
        $this->addColumn('report', 'effectiveness_keywords', $this->text()->null());
        $this->addColumn('report', 'performance_keywords', $this->text()->null());
        $this->addColumn('report', 'competences_keywords', $this->text()->null());
        $this->addColumn('report', 'emergents_keywords', $this->text()->null());
        $this->addColumn('report', 'relations_keywords', $this->text()->null());

        $this->addColumn('individual_report', 'perception_keywords', $this->text()->null());
        $this->addColumn('individual_report', 'relations_keywords', $this->text()->null());
        $this->addColumn('individual_report', 'competences_keywords', $this->text()->null());
        $this->addColumn('individual_report', 'emergents_keywords', $this->text()->null());
        $this->addColumn('individual_report', 'performance_keywords', $this->text()->null());

        $this->dropColumn('report', 'summary');
    }

    public function down()
    {
        $this->dropColumn('report', 'introduction_keywords');
        $this->dropColumn('report', 'effectiveness_keywords');
        $this->dropColumn('report', 'performance_keywords');
        $this->dropColumn('report', 'competences_keywords');
        $this->dropColumn('report', 'emergents_keywords');
        $this->dropColumn('report', 'relations_keywords');
        $this->dropColumn('report', 'action_plan_keywords');

        $this->dropColumn('individual_report', 'perception_keywords');
        $this->dropColumn('individual_report', 'relations_keywords');
        $this->dropColumn('individual_report', 'competences_keywords');
        $this->dropColumn('individual_report', 'emergents_keywords');
        $this->dropColumn('individual_report', 'performance_keywords');

        $this->addColumn('report', 'summary', $this->text()->null());
    }

}
