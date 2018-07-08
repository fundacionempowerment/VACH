<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment sent');
?>
<div class="col-md-12">
    <div class="text-center">
        <div class="jumbotron">
            <h3 class="text-success"><?= Yii::t('payment', 'Payment link successfully sent!') ?></h3>
            <h3><?= Yii::t('payment', "We are going to notify you when payment is received.") ?></h3>
            <br>
            <br>
            <?= Html::a(Yii::t('stock', 'Go to My Licences'), ['/stock'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>


