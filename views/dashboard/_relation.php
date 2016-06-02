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

if ($type == Wheel::TYPE_GROUP)
    $title = Yii::t('dashboard', 'Group Relations Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Relations Matrix');
else
    $title = Yii::t('dashboard', 'Individual Relations Matrix');

$token = rand(100000, 999999);

$forwardData = [];
foreach ($data as $datum) {
    if ($datum['observer_id'] == $memberId && $datum['observed_id'] == $memberId)
        $forwardData[] = $datum;
}

foreach ($data as $datum) {
    if ($datum['observer_id'] != $memberId && $datum['observed_id'] == $memberId)
        $forwardData[] = $datum;
}

$backwardData = [];
foreach ($data as $datum) {
    if ($datum['observer_id'] == $memberId && $datum['observed_id'] == $memberId)
        $backwardData[] = $datum;
}

foreach ($data as $datum) {
    if ($datum['observer_id'] == $memberId && $datum['observed_id'] != $memberId)
        $backwardData[] = $datum;
}

$width = 500;
$height = 350;
if (count($forwardData) < 4)
    $height = 150;
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<?php if (count($forwardData) > 0) { ?>
    <div id="fdiv<?= $token ?>" class="col-xs-12 col-md-6" >
        <canvas id="canvas<?= $token ?>f" height="<?= $height ?>" width="<?= $width ?>" class="img-responsive center-block"></canvas>
    </div>
    <div id="bdiv<?= $token ?>" class="col-xs-12 col-md-6" >
        <canvas id="canvas<?= $token ?>b" height="<?= $height ?>" width="<?= $width ?>" class="img-responsive center-block"></canvas>
    </div>
<?php } ?>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false && $memberId > 0) { ?>
    <div class="col-md-6 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('fdiv$token')"]) ?>
    </div>
<div class="col-md-6 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('bdiv$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
<br>
<?php if (count($forwardData) > 0) { ?>
    <script>
        var forwardData<?= $token ?> = <?= Json::encode($forwardData) ?>;
        var backwardData<?= $token ?> = <?= Json::encode($backwardData) ?>;
        relations.push("<?= $token ?>");
        forwardRelationsData.push(forwardData<?= $token ?>);
        backwardRelationsData.push(backwardData<?= $token ?>);
    </script>
<?php } ?>
