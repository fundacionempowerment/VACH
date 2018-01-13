<?php

use yii\db\Migration;

class m170326_043036_bind_answer_to_question extends Migration
{

    public function up()
    {
        $this->addColumn('wheel_answer', 'question_id', $this->integer()->null());

        $this->execute('UPDATE `wheel_answer` SET `question_id`= ('
                . 'SELECT `wheel_question`.`question_id` '
                . 'FROM `wheel_question` '
                . 'WHERE '
                . '`wheel_question`.`dimension` = `wheel_answer`.`dimension` '
                . 'AND `wheel_question`.`order` = `wheel_answer`.`answer_order` '
                . 'AND `wheel_question`.`type` = ( '
                . 'SELECT `wheel`.`type` FROM `wheel` WHERE `wheel`.`id` = `wheel_answer`.`wheel_id` '
                . ')'
                . ' )');
    }

    public function down()
    {
        $this->dropColumn('wheel_answer', 'question_id');
    }

}
