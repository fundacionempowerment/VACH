<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment wait');

$this->registerJs("setTimeout(function(){ window.location.reload(1); }, 5000);", \yii\web\View::POS_END, 'refresh-page');
?>
<div class="col-md-12">    
    <div class="text-center">
        <div class="jumbotron">
            <?= Yii::$app->user->isGuest ? Html::img('@web/images/logo.png') . '<br><br>' : '' ?>
            <h3><?= Yii::t('payment', 'Please, wait in this page.') ?></h3>
            <h3><?= Yii::t('payment', "We are waiting payment broker confirmation.") ?></h3>
            <br>
            <br>
            <img src="images/red-loading.gif" />
        </div>        
    </div>
</div>




