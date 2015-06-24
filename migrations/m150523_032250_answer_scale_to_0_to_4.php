<?php

use yii\db\Migration;

class m150523_032250_answer_scale_to_0_to_4 extends Migration {

  public function up() {
    $this->execute('update {{%wheel_answer}} set
            answer_value = case
                when answer_value = 9 or answer_value = 8 then 4
                when answer_value = 7 or answer_value = 6 then 3
                when answer_value = 5 or answer_value = 4 then 2
                when answer_value = 3 or answer_value = 2 then 1
                when answer_value = 1 or answer_value = 0 then 0
                end');
  }

  public function down() {
    $this->execute('update {{%wheel_answer}} set
            answer_value = (answer_value * 2) + 1');
  }

}
