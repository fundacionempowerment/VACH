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

$linealWidth = 350 * 1.5;
$linealHeight = 200;
$token = rand(100000, 999999);

$dimensions = WheelQuestion::getDimensionNames($type);
?>
<h3><?= $title ?></h3>
<div class="col-xs-push-2 col-xs-8 col-md-push-2 col-md-8" >
    <canvas id="canvas<?= $token ?>" height="<?= $linealHeight ?>" width="<?= $linealWidth ?>" class="img-responsive"></canvas>
</div>
<div class="clearfix"></div>
<script>
    var data<?= $token ?> = <?= Json::encode($data) ?>;

    matrixes.push("<?= $token ?>");
    matrixesData.push(data<?= $token ?>);
</script>