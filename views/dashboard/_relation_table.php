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
<div class="col-md-12">
    <table class="table table-bordered table-hover">
        <tr>
            <td>
                <?= Yii::t('wheel', "Observer \\ Observed") ?>
            </td>
            <?php
            foreach ($members as $id => $member)
                if ($id > 0) {
                    ?>
                    <td>
                        <?= $member ?>
                    </td>
                <?php } ?>
            <td>
                <?= Yii::t('app', 'Average') ?>
            </td>
        </tr>
        <?php
        $observed_sum = [];
        foreach ($members as $observerId => $observer)
            if ($observerId > 0) {
                $observer_sum = 0;
                $observer_count = 0;
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
                                    if ($datum['value'] > Yii::$app->params['good_consciousness'])
                                        $class = 'success';
                                    else if ($datum['value'] < Yii::$app->params['minimal_consciousness'])
                                        $class = 'danger';
                                    else
                                        $class = 'warning';

                                    echo Html::tag('td', round($datum['value'] * 100 / 4, 1) . ' %', ['class' => $class]);
                                    $observer_sum += $datum['value'];
                                    $observer_count++;
                                    if (!isset($observed_sum[$observedId]))
                                        $observed_sum[$observedId] = 0;
                                    $observed_sum[$observedId] += $datum['value'];
                                }
                            }
                        }
                    if ($observer_count > 0) {
                        if ($observer_sum / $observer_count > Yii::$app->params['good_consciousness'])
                            $class = 'success';
                        else if ($datum['value'] < Yii::$app->params['minimal_consciousness'])
                            $class = 'danger';
                        else
                            $class = 'warning';

                        echo Html::tag('td', round($observer_sum / $observer_count * 100 / 4, 1) . ' %', ['class' => $class]);
                    }
                    ?>
                </tr>
            <?php } ?>
        <tr>
            <td>
                <?= Yii::t('app', 'Average') ?>
            </td>
            <?php
            if ($observer_count > 0)
                foreach ($observed_sum as $sum) {
                    if ($sum / $observer_count > Yii::$app->params['good_consciousness'])
                        $class = 'success';
                    else if ($datum['value'] < Yii::$app->params['minimal_consciousness'])
                        $class = 'danger';
                    else
                        $class = 'warning';

                    echo Html::tag('td', round($sum / $observer_count * 100 / 4, 1) . ' %', ['class' => $class]);
                }
            ?>
        </tr>
    </table>
</div>
<div class="clearfix"></div>