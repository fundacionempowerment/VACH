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

$drawing_data = [];
foreach ($data as $datum) {
    if ($datum['observer_id'] == $memberId && $datum['observed_id'] == $memberId)
        $drawing_data[] = $datum;
}

foreach ($data as $datum) {
    if ($datum['observer_id'] != $memberId && $datum['observed_id'] == $memberId)
        $drawing_data[] = $datum;
}

$width = 800;
$height = 400;
if (count($drawing_data) < 4)
    $height = 150;
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<?php if (count($drawing_data) > 0) { ?>
    <div id="div<?= $token ?>" class="col-xs-12 col-md-push-1 col-md-10" >
        <canvas id="canvas<?= $token ?>" height="<?= $height ?>" width="<?= $width ?>" class="img-responsive center-block"></canvas>
    </div>
<?php } ?>
<div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app','Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
</div>
<div class="clearfix"></div>
<br>
<?php if (count($drawing_data) > 0) { ?>
    <script>
        var data<?= $token ?> = <?= Json::encode($drawing_data) ?>;

        relations.push("<?= $token ?>");
        relationsData.push(data<?= $token ?>);
    </script>
<?php } ?>
