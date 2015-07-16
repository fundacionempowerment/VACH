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
    $title = Yii::t('dashboard', 'Group Potential Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Potential Matrix');
else
    $title = Yii::t('dashboard', 'Individual Potential Matrix');

$linealWidth = 800;
$linealHeight = 400;
$token = rand(100000, 999999);

$dimensions = WheelQuestion::getDimensionNames($type);
?>
<h3><?= $title ?></h3>
<div class="col-xs-12 col-md-push-1 col-md-10" >
    <canvas id="canvas<?= $token ?>" height="<?= $linealHeight ?>" width="<?= $linealWidth ?>" class="img-responsive center-block"></canvas>
</div>
<div class="clearfix"></div>
<script>
    var data<?= $token ?> = <?= Json::encode($data) ?>;

    matrixes.push("<?= $token ?>");
    matrixesData.push(data<?= $token ?>);
</script>