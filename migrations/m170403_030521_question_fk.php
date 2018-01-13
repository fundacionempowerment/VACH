<?php

use yii\db\Migration;

class m170403_030521_question_fk extends Migration
{

    public function up()
    {
        $this->addForeignKey('fk_wheel_question_question', 'wheel_question', 'question_id', 'question', 'id');
        $this->addForeignKey('fk_wheel_answer_question', 'wheel_answer', 'question_id', 'question', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_wheel_question_question', 'wheel_question');
        $this->dropForeignKey('fk_wheel_answer_question', 'wheel_answer');
    }

}
