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

// Function to calculate square of value - mean
function sd_square($x, $mean) {
    return pow($x - $mean, 2);
}

// Function to calculate standard deviation (uses sd_square)
function sd($array) {

// square root of sum of squares devided by N-1
    return sqrt(array_sum(array_map("sd_square", $array, array_fill(0, count($array), (array_sum($array) / count($array))))) / (count($array) - 1));
}

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
$allConsciousness = $sum / (count($members) - 1);

$standar_deviation = sd($gaps);
?>
<h3><?= $title ?></h3>
<div class="col-md-12">
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
                    <?= $value ?>
                </td>
            <?php } ?>
        </tr>       
        <tr>
            <td>
                <?= Yii::t('dashboard', 'How They see me') ?>
            </td>
            <?php foreach ($howTheySeeMe as $value) { ?>
                <td>
                    <?= $value ?>
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
<div class="clearfix"></div>
