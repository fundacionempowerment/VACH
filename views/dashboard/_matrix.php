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
    $title = Yii::t('dashboard', 'Group Performance Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Performance Matrix');
else
    $title = Yii::t('dashboard', 'Individual Performance Matrix');

$linealWidth = 800;
$linealHeight = 400;
$token = rand(100000, 999999);

$dimensions = WheelQuestion::getDimensionNames($type);

$matrix_data['data'] = $data;
$matrix_data['memberId'] = $memberId;
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="col-xs-12 col-md-push-1 col-md-10" >
    <canvas id="canvas<?= $token ?>" height="<?= $linealHeight ?>" width="<?= $linealWidth ?>" class="img-responsive center-block"></canvas>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
<script>
    var data<?= $token ?> = <?= Json::encode($matrix_data) ?>;

    matrixes.push("<?= $token ?>");
    matrixesData.push(data<?= $token ?>);
</script>