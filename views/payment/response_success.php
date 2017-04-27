<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment success');
?>
<div class="col-md-12">    
    <div class="text-center">
        <div class="jumbotron">
            <h3 class="text-success"><?= Yii::t('payment', 'Payment successfull!') ?></h3>
            <br>
            <br>
            <?= Html::a(Yii::t('stock', 'Go to My Licences'), ['/stock'], ['class' => 'btn btn-success']) ?>
        </div>        
    </div>
</div>


