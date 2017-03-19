<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$radarDiameter = 350;
$token = rand(100000, 999999);
?>
<div class="clearfix"></div>
<div id="div<?= $token ?>" class="col-xs-push-1 col-xs-10 col-md-push-2 col-md-8 text-center" >
    <?=
    Html::img(Url::toRoute(["/graph/radar",
                'assessmentId' => $filter->assessmentId,
                'memberId' => $filter->memberId,
                'wheelType' => $wheelType]), ['class' => 'img-responsive'])
    ?>
</div>
<div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
</div>
<div class="clearfix"></div>
<script>
</script>