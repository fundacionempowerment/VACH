<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$token = rand(100000, 999999);
?>
<div class="clearfix"></div>
<div id="div<?= $token ?>" class="col-xs-12 col-md-12 text-center" >
    <?=
    Html::img(Url::toRoute(["/graph/relations",
                'teamId' => $teamId,
                'memberId' => $memberId,
                'wheelType' => $wheelType]), ['class' => 'img-responsive'])
    ?>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false && $memberId > 0) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
<br>
