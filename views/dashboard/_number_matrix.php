<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;
use yii\helpers\Json;
use app\controllers\Utils;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if ($type == Wheel::TYPE_GROUP)
    $title = Yii::t('dashboard', 'Group Consciousness and Responsability Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Consciousness and Responsability Matrix');
else
    $title = Yii::t('dashboard', 'Individual Consciousness and Responsability Matrix');

$howISeeMe = [];
foreach ($members as $id => $member)
    if ($id > 0)
        foreach ($data as $datum) {
            if ($datum['observer_id'] == $id && $datum['observed_id'] == $id) {
                $howISeeMe[] = $datum['value'];
            }
        }

$howTheySeeMe = [];
foreach ($members as $id => $member)
    if ($id > 0) {
        $sum = 0;
        foreach ($data as $datum) {
            if ($datum['observer_id'] != $id && $datum['observed_id'] == $id) {
                $sum += $datum['value'];
            }
        }
        $howTheySeeMe[] = $sum / (count($members) - 2);
    }

if (count($howISeeMe) != count($howTheySeeMe))
    return;

$sum = 0;
for ($i = 0; $i < count($howTheySeeMe); $i++)
    $sum += $howTheySeeMe[$i];
$allTheySee = $sum / (count($members) - 1);

$sum = 0;
$gaps = [];
for ($i = 0; $i < count($howTheySeeMe); $i++) {
    $sum += $howTheySeeMe[$i] - $howISeeMe[$i];
    $gaps [] = $howTheySeeMe[$i] - $howISeeMe[$i];
}

$standar_deviation = Utils::standard_deviation($gaps);
$token = rand(100000, 999999);
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="row col-md-12">
    <table class="table table-bordered table-hover">
        <tr>
            <td>
                <?= Yii::t('app', 'Description') ?>
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
        <tr>
            <td>
                <?= Yii::t('dashboard', 'How I see me') ?>
            </td>
            <?php foreach ($howISeeMe as $value) { ?>
                <td>
                    <?= round($value, 2) ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <td>
                <?= Yii::t('dashboard', 'How they see me') ?>
            </td>
            <?php foreach ($howTheySeeMe as $value) { ?>
                <td>
                    <?= round($value, 2) ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Monofactorial productivity') ?>
            </td>
            <?php foreach ($howTheySeeMe as $value) { ?>
                <td>
                    <?= round($value / 4 * 100, 1) . ' %' ?>
                </td>
            <?php } ?>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Responsability') ?>
            </td>
            <?php foreach ($howTheySeeMe as $value) { ?>
                <td class="<?= $value < $allTheySee ? 'warning' : 'success' ?>">
                    <?= $value < $allTheySee ? Yii::t('app', 'Low') : Yii::t('app', 'High') ?>
                </td>
            <?php } ?>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Avg. mon. prod.') ?>
            </td>
            <td>
                <?= round($allTheySee / 4 * 100, 1) . ' %' ?>
            </td>
            <td>
                <?= Yii::t('dashboard', 'St. dev.') ?>
            </td>
            <td>
                <?= round($standar_deviation / 4 * 100, 1) . ' %' ?>
            </td>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Cons. gap') ?>
            </td>
            <?php for ($i = 0; $i < count($howTheySeeMe); $i++) { ?>
                <td>
                    <?= round(abs($gaps[$i]) / 4 * 100, 1) . ' %' ?>
                </td>
            <?php } ?>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Consciousness') ?>
            </td>
            <?php for ($i = 0; $i < count($howTheySeeMe); $i++) { ?>
                <td class="<?= abs($gaps[$i]) > $standar_deviation ? 'warning' : 'success' ?>">
                    <?= abs($gaps[$i]) > $standar_deviation ? Yii::t('app', 'Low') : Yii::t('app', 'High') ?>
                </td>
            <?php } ?>
        </tr> 
    </table>
</div>
<div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app','Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
</div>
<div class="clearfix"></div>
