<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$token = rand(100000, 999999);
?>
<div id="div<?= $token ?>" class="row col-md-12">
    <?=
    Html::img(Url::toRoute(["/graph/gauges",
                'teamId' => $teamId,
                'memberId' => $memberId,
                'wheelType' => $wheelType]), ['class' => 'img-responsive'])
    ?>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
