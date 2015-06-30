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

$width = 525;
$height = 250;
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
?>
<h3><?= $title ?></h3>
<div class="col-md-push-2 col-md-8" >
    <canvas id="canvas<?= $token ?>" height="<?= $height ?>" width="<?= $width ?>" class="img-responsive center-block"></canvas>
</div>
<div class="col-md-12">
    <table class="table table-bordered table-hover">
        <tr>
            <td>
                <?= Yii::t('wheel', 'observer \\ observed') ?>
            </td>
            <?php
            foreach ($members as $id => $member)
                if ($id > 0) {
                    ?>
                    <td>
                        <?= $member ?>
                    </td>
                <?php } ?>
        </tr>
        <?php
        foreach ($members as $observerId => $observer)
            if ($observerId > 0) {
                ?>
                <tr>
                    <td>
                        <?= $observer ?>
                    </td>
                    <?php
                    foreach ($members as $observedId => $observed)
                        if ($observedId > 0) {
                            foreach ($data as $datum) {
                                if ($datum['observer_id'] == $observerId && $datum['observed_id'] == $observedId) {
                                    if ($datum['value'] > 2.8)
                                        $class = 'success';
                                    else if ($datum['value'] < 1.6)
                                        $class = 'danger';
                                    else
                                        $class = 'warning';

                                    echo Html::tag('td', round($datum['value'] * 100 / 4, 1) . ' %', ['class' => $class]);
                                }
                            }
                        }
                    ?>
                </tr>
            <?php } ?>
    </table>
</div>
<div class="clearfix"></div>
<script>
    var data<?= $token ?> = <?= Json::encode($drawing_data) ?>;

    relations.push("<?= $token ?>");
    relationsData.push(data<?= $token ?>);
</script>