<?php

use yii\db\Migration;

class m170428_033049_add_payment_commision_fields extends Migration
{

    public function up()
    {
        $this->addColumn('payment', 'currency', $this->string(3)->notNull()->defaultValue('USD'));
        $this->addColumn('payment', 'rate', $this->decimal(10, 2)->null());
        $this->addColumn('payment', 'commision', $this->decimal(10, 2)->null());
        $this->addColumn('payment', 'commision_currency', $this->string(3)->null());
    }

    public function down()
    {
        $this->dropColumn('payment', 'currency');
        $this->dropColumn('payment', 'rate');
        $this->dropColumn('payment', 'commision');
        $this->dropColumn('payment', 'commision_currency');
    }

}
