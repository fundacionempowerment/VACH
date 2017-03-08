<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\Currency;
use app\models\Account;
use app\models\Payment;

/* @var $model app\models\BuyModel */

$this->title = Yii::t('app', 'Payment confirmation');

?>
<div class="col-md-12">    
    <div class="text-center">
        <div class="jumbotron">
            <?php
            switch ($model->status) {
                case Payment::STATUS_INIT:
                    echo $this->render('_pending', ['model' => $model]);
                    break;
                case Payment::STATUS_PAID:
                    echo $this->render('_success');
                    break;
                default :
                    echo $this->render('_error');
                    break;
            }
            ?>
        </div>        
    </div>
</div>
