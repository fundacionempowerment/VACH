<?php

use yii\db\Migration;

class m170518_060529_add_stock_assessment_relation extends Migration
{

    public function safeUp()
    {
        $this->addColumn('stock', 'assessment_id', $this->integer()->null());

        $this->addForeignKey('fk_stock_assessment', 'stock', 'assessment_id', 'assessment', 'id');

        $consumptions = $this->db->createCommand('SELECT * FROM `stock` WHERE `quantity` < 0 ORDER BY `stock`.`id` DESC')->queryAll();
        $assessments = $this->db->createCommand('SELECT `assessment`.`id`, `team`.`coach_id`, count(DISTINCT `team_member`.`person_id`) as `quantity`
            FROM `assessment` 
            INNER JOIN `team` ON `team`.`id` = `assessment`.`team_id` 
            INNER JOIN `team_member` ON `team_member`.`team_id` = `team`.`id`
            WHERE `team`.`blocked` = 1
            GROUP BY `assessment`.`id`, `team`.`coach_id`
            ORDER BY `assessment`.`id` DESC')->queryAll();

        foreach ($consumptions as $consumption) {
            echo "stock = " . $consumption['id'] . "\n";
            for ($i = 0; $i < count($assessments); $i++) {
                echo "assessment = " . $assessments[$i]['id'] . "\n";
                echo "quantity = " . $assessments[$i]['quantity'] . "\n";
                if ($consumption['coach_id'] == $assessments[$i]['coach_id'] && abs($consumption['quantity']) <= $assessments[$i]['quantity']) {
                    $this->update('stock', ['assessment_id' => $assessments[$i]['id']], ['id' => $consumption['id']]);
                    $assessments[$i]['quantity'] += $consumption['quantity'];
                    echo "used\n";
                    echo "new quantity = " . $assessments[$i]['quantity'] . "\n";
                    echo "------\n";
                    break;
                }
            }
        }
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_stock_assessment', 'stock');

        $this->dropColumn('stock', 'assessment_id');
    }

}
