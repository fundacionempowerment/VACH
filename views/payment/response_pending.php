<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment pending');
?>
<div class="col-md-12">    
    <div class="text-center">
        <div class="jumbotron">
            <h3><?= Yii::t('payment', "Your payment is pending.") ?></h3>
            <h3><?= Yii::t('payment', "We are going to notify you when it is received.") ?></h3>
            <br>
            <br>
            <?= Html::a(Yii::t('stock', 'Go to My Licences'), ['/stock'], ['class' => 'btn btn-success']) ?>
        </div>        
    </div>
</div>



