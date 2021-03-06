<?php

use yii\helpers\Html;
use app\models\Wheel;
use app\components\Utils;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$title = Yii::t('dashboard', 'Consciousness and Responsability Matrix') . ' ' .
    ($type == 1 ? $team->teamType->level_1_name : $team->teamType->level_2_name);

if (!empty($member)) {
    $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
} else {
    $title .= ' ' . Yii::t('app', 'of the team');
}

$howISeeMe = [];
foreach ($members as $id => $name) {
    if ($id > 0) {
        foreach ($data as $datum) {
            if ($datum['observer_id'] == $id && $datum['observed_id'] == $id) {
                $howISeeMe[] = $datum['value'];
            }
        }
    }
}

$howTheySeeMe = [];
foreach ($members as $id => $name) {
    if ($id > 0) {
        $sum = 0;
        foreach ($data as $datum) {
            if ($datum['observer_id'] != $id && $datum['observed_id'] == $id) {
                $sum += $datum['value'];
            }
        }
        $howTheySeeMe[] = $sum / (count($members) - 2);
    }
}

if (count($howISeeMe) != count($howTheySeeMe)) {
    return;
}

$sum = 0;
for ($i = 0; $i < count($howTheySeeMe); $i++) {
    $sum += $howTheySeeMe[$i];
}
$allTheySee = $sum / (count($members) - 1);

$sum = 0;
$gaps = [];
for ($i = 0; $i < count($howTheySeeMe); $i++) {
    $sum += $howTheySeeMe[$i] - $howISeeMe[$i];
    $gaps [] = $howTheySeeMe[$i] - $howISeeMe[$i];
}

$standar_deviation = Utils::standard_deviation($gaps);
$productivityDelta = Utils::variance($howTheySeeMe);
$mean_gap = Utils::absolute_mean($gaps);
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
            foreach ($members as $id => $name) {
                if ($id > 0) {
                    ?>
                    <td>
                        <?= $name ?>
                    </td>
                <?php } ?>
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
                    <?= round($value / 4 * 100, 1) . '%' ?>
                </td>
            <?php } ?>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Responsability') ?>
            </td>
            <?php foreach ($howTheySeeMe as $value) { ?>
                <td class="<?= $value < $allTheySee ? 'warning' : 'success' ?>">
                    <?= Utils::productivityText($value, $allTheySee, $productivityDelta) ?>
                </td>
            <?php } ?>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Avg. mon. prod.') ?>
            </td>
            <td>
                <?= round($allTheySee / 4 * 100, 1) . '%' ?>
            </td>
            <td colspan="2">
                <?= Yii::t('dashboard', 'Prod. deviation') ?>
            </td>
            <td>
                <?= (round($productivityDelta / 4 * 100, 1)) . '%' ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Cons. gap') ?>
            </td>
            <?php for ($i = 0; $i < count($howTheySeeMe); $i++) { ?>
                <td>
                    <?= round(abs($gaps[$i]) / 4 * 100, 1) . '%' ?>
                </td>
            <?php } ?>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Consciousness') ?>
            </td>
            <?php for ($i = 0; $i < count($howTheySeeMe); $i++) { ?>
                <td class="<?= abs($gaps[$i]) > ($mean_gap) ? 'warning' : 'success' ?>">
                    <?= abs($gaps[$i]) > ($mean_gap) ? Yii::t('app', 'Low') : Yii::t('app', 'High') ?>
                </td>
            <?php } ?>
        </tr> 
        <tr>
            <td>
                <?= Yii::t('dashboard', 'Avg. conc. gap') ?>
            </td>
            <td>
                <?= round($mean_gap / 4 * 100, 1) . '%' ?>
            </td>

        </tr>
    </table>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
<?=
$this->render('_rankings', [
    'type' => $type,
    'memberId' => $memberId,
    'member' => $member,
    'team' => $team,
    'members' => $members,
    'howISeeMe' => $howISeeMe,
    'howTheySeeMe' => $howTheySeeMe,
    'gaps' => $gaps,
    'mean_gap' => round($mean_gap / 4 * 100, 1),
    'allTheySee' => round($allTheySee / 4 * 100, 1),
]);
?>
