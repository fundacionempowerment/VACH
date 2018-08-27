<?php

use yii\db\Migration;

/**
 * Class m180123_045134_populate_report_fields
 */
class m180123_045134_populate_report_fields extends Migration
{

    const REPORT_FIELDS = [
        'introduction',
        'effectiveness',
        'performance',
        'competences',
        'emergents',
        'relations',
        'introduction_keywords',
        'effectiveness_keywords',
        'performance_keywords',
        'competences_keywords',
        'emergents_keywords',
        'relations_keywords',
        'action_plan',
    ];
    const INDIVIDUAL_REPORT_FIELDS = [
        'perception',
        'relations',
        'competences',
        'emergents',
        'performance',
        'perception_keywords',
        'relations_keywords',
        'competences_keywords',
        'emergents_keywords',
        'performance_keywords',
    ];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $reports = $this->db->createCommand('SELECT * FROM `report`')->queryAll();
        foreach ($reports as $report) {
            foreach (self::REPORT_FIELDS as $field) {
                $this->createField('report', $report, $field);
            }
        }

        $individual_reports = $this->db->createCommand('SELECT * FROM `individual_report`')->queryAll();
        foreach ($individual_reports as $individual_report) {
            foreach (self::INDIVIDUAL_REPORT_FIELDS as $field) {
                $this->createField('individual_report', $individual_report, $field);
            }
        }
    }

    private function createField($table, $data, $field)
    {
        $content = trim($data[$field]);
        if (!empty($content)) {
            Yii::$app->db->createCommand()->insert('report_field', [
                'content' => $content,
            ])->execute();

            $id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand()->update($table, [$field . '_id' => $id,], ['id' => $data['id']]
            )->execute();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180123_045134_populate_report_fields cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180123_045134_populate_report_fields cannot be reverted.\n";

      return false;
      }
     */
}
