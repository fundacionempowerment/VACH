<?php

use yii\db\Migration;

/**
 * Class m180123_043834_split_report_fields
 */
class m180123_043834_split_report_fields extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('report_field', [
            'report_field_id' => $this->primaryKey(),
            'content' => $this->text(),
                ], $tableOptions);

        $this->addColumn('report', 'introduction_id', $this->integer());
        $this->addColumn('report', 'effectiveness_id', $this->integer());
        $this->addColumn('report', 'performance_id', $this->integer());
        $this->addColumn('report', 'competences_id', $this->integer());
        $this->addColumn('report', 'emergents_id', $this->integer());
        $this->addColumn('report', 'relations_id', $this->integer());
        $this->addColumn('report', 'introduction_keywords_id', $this->integer());
        $this->addColumn('report', 'effectiveness_keywords_id', $this->integer());
        $this->addColumn('report', 'performance_keywords_id', $this->integer());
        $this->addColumn('report', 'competences_keywords_id', $this->integer());
        $this->addColumn('report', 'emergents_keywords_id', $this->integer());
        $this->addColumn('report', 'relations_keywords_id', $this->integer());
        $this->addColumn('report', 'action_plan_id', $this->integer());

        $this->addColumn('individual_report', 'perception_id', $this->integer());
        $this->addColumn('individual_report', 'relations_id', $this->integer());
        $this->addColumn('individual_report', 'competences_id', $this->integer());
        $this->addColumn('individual_report', 'emergents_id', $this->integer());
        $this->addColumn('individual_report', 'performance_id', $this->integer());

        $this->addColumn('individual_report', 'perception_keywords_id', $this->integer());
        $this->addColumn('individual_report', 'relations_keywords_id', $this->integer());
        $this->addColumn('individual_report', 'competences_keywords_id', $this->integer());
        $this->addColumn('individual_report', 'emergents_keywords_id', $this->integer());
        $this->addColumn('individual_report', 'performance_keywords_id', $this->integer());

        $this->addForeignKey('fk_report_introduction', 'report', 'introduction_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_effectiveness', 'report', 'effectiveness_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_performance', 'report', 'performance_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_competences', 'report', 'competences_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_emergents', 'report', 'emergents_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_relations', 'report', 'relations_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_introduction_keywords', 'report', 'introduction_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_effectiveness_keywords', 'report', 'effectiveness_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_performance_keywords', 'report', 'performance_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_competences_keywords', 'report', 'competences_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_emergents_keywords', 'report', 'emergents_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_relations_keywords', 'report', 'relations_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_report_action_plan', 'report', 'action_plan_id', 'report_field', 'report_field_id');

        $this->addForeignKey('fk_individual_report_perception', 'individual_report', 'perception_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_relations', 'individual_report', 'relations_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_competences', 'individual_report', 'competences_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_emergents', 'individual_report', 'emergents_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_performance', 'individual_report', 'performance_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_perception_keywords', 'individual_report', 'perception_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_relations_keywords', 'individual_report', 'relations_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_competences_keywords', 'individual_report', 'competences_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_emergents_keywords', 'individual_report', 'emergents_keywords_id', 'report_field', 'report_field_id');
        $this->addForeignKey('fk_individual_report_performance_keywords', 'individual_report', 'performance_keywords_id', 'report_field', 'report_field_id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180123_043834_split_report_fields cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180123_043834_split_report_fields cannot be reverted.\n";

      return false;
      }
     */
}
