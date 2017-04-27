<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment declined');
?>
<div class="col-md-12">    
    <div class="text-center">
        <div class="jumbotron">
            <h3><?= Yii::t('payment', "Your payment has been declined. Please try again.") ?></h3>
            <h3><?= Yii::t('payment', "Please, try again.") ?></h3>
            <br>
            <br>
            <?= Html::a(Yii::t('stock', 'Go to My Licences'), ['/stock'], ['class' => 'btn btn-success']) ?>
        </div>        
    </div>
</div>

