<?php

use yii\db\Migration;

class m170818_033118_add_swap_competences extends Migration
{

    public function safeUp()
    {
        $this->swap_answers();
        $this->swap_questions();
    }

    public function safeDown()
    {
        $this->swap_questions();
        $this->swap_answers();
    }

    private function swap_answers()
    {
        $org_wheels = $this->db
                ->createCommand('SELECT `id` FROM `wheel` WHERE `type` = 2')
                ->queryColumn('id');

        $this->update('wheel_answer', ['answer_order' => new yii\db\Expression('answer_order + 8')], [
            'dimension' => 4,
            'wheel_id' => $org_wheels,
        ]);

        $this->update('wheel_answer', ['answer_order' => new yii\db\Expression('answer_order - 8')], [
            'dimension' => 5,
            'wheel_id' => $org_wheels,
        ]);

        // Swap dimensions

        $this->update('wheel_answer', ['dimension' => 111], [
            'dimension' => 4,
            'wheel_id' => $org_wheels,
        ]);

        $this->update('wheel_answer', ['dimension' => 4], [
            'dimension' => 5,
            'wheel_id' => $org_wheels,
        ]);

        $this->update('wheel_answer', ['dimension' => 5], [
            'dimension' => 111,
            'wheel_id' => $org_wheels,
        ]);
    }

    private function swap_questions()
    {
        $this->update('wheel_question', ['order' => new yii\db\Expression('`order` + 8')], [
            'dimension' => 4,
            'type' => 2,
        ]);

        $this->update('wheel_question', ['order' => new yii\db\Expression('`order` - 8')], [
            'dimension' => 5,
            'type' => 2,
        ]);

        // Swap dimensions

        $this->update('wheel_question', ['dimension' => 111], [
            'dimension' => 4,
            'type' => 2,
        ]);

        $this->update('wheel_question', ['dimension' => 4], [
            'dimension' => 5,
            'type' => 2,
        ]);

        $this->update('wheel_question', ['dimension' => 5], [
            'dimension' => 111,
            'type' => 2,
        ]);
    }

}
