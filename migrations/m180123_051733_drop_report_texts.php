<?php

use yii\db\Migration;

/**
 * Class m180123_051733_drop_report_texts
 */
class m180123_051733_drop_report_texts extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('report', 'introduction');
        $this->dropColumn('report', 'effectiveness');
        $this->dropColumn('report', 'performance');
        $this->dropColumn('report', 'competences');
        $this->dropColumn('report', 'emergents');
        $this->dropColumn('report', 'relations');
        $this->dropColumn('report', 'introduction_keywords');
        $this->dropColumn('report', 'effectiveness_keywords');
        $this->dropColumn('report', 'performance_keywords');
        $this->dropColumn('report', 'competences_keywords');
        $this->dropColumn('report', 'emergents_keywords');
        $this->dropColumn('report', 'relations_keywords');
        $this->dropColumn('report', 'action_plan');

        $this->dropColumn('individual_report', 'perception');
        $this->dropColumn('individual_report', 'relations');
        $this->dropColumn('individual_report', 'competences');
        $this->dropColumn('individual_report', 'emergents');
        $this->dropColumn('individual_report', 'performance');
        $this->dropColumn('individual_report', 'perception_keywords');
        $this->dropColumn('individual_report', 'relations_keywords');
        $this->dropColumn('individual_report', 'competences_keywords');
        $this->dropColumn('individual_report', 'emergents_keywords');
        $this->dropColumn('individual_report', 'performance_keywords');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180123_051733_drop_report_texts cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180123_051733_drop_report_texts cannot be reverted.\n";

      return false;
      }
     */
}
