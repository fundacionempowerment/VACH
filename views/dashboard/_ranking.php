<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if ($type == Wheel::TYPE_GROUP)
    $title = Yii::t('dashboard', 'Group Rankings');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Rankings');
else
    $title = Yii::t('dashboard', 'Individual Rankings');

if (!empty($member)) {
    $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
} else {
    $title .= ' ' . Yii::t('app', 'of the team');
}

unset($members[0]);

$i = 0;
foreach ($members as $id => $name) {
    $ranking_consciouness[$name] = round(abs($gaps[$i]) / 4 * 100, 1);
    $i++;
}

$i = 0;
foreach ($members as $id => $name) {
    $ranking_productivity[$name] = round($howTheySeeMe[$i] / 4 * 100, 1);
    $i++;
}

$i = 0;
foreach ($members as $id => $name) {
    $ranking_both[$name] = round($howTheySeeMe[$i] * (4 - abs($gaps[$i])) / 4 / 4 * 100, 1);
    $i++;
}

asort($ranking_consciouness);
arsort($ranking_productivity);
arsort($ranking_both);

$token = rand(100000, 999999);
?>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="row col-md-12">
    <div class="col-md-6">
        <h4><?= Yii::t('dashboard', 'Consciouness Ranking') ?></h4>
        <table class="table table-bordered table-hover">
            <tr>
                <th>
                    <?= Yii::t('dashboard', '#') ?>
                </th>
                <th>
                    <?= Yii::t('dashboard', 'Person') ?>
                </th>
                <th>
                    <?= Yii::t('dashboard', 'Value') ?>
                </th>
            </tr>
            <?php
            $i = 1;
            foreach ($ranking_consciouness as $name => $value) {
                ?>
                <tr class="<?= !empty($member) && ($member->fullname == $name) ? ($value <= $mean_gap ? 'success' : 'warning' ) : '' ?>">
                    <td>
                        <?= $i++ ?>                        
                    </td>                    
                    <td>
                        <?= $name ?>
                    </td>
                    <td>
                        <?= $value . '%' ?>
                    </td>
                </tr>
            <?php } ?>

        </table>        
    </div>
    <div class="col-md-6">
        <h4><?= Yii::t('dashboard', 'Productivity Ranking') ?></h4>
        <table class="table table-bordered table-hover">
            <tr>
                <th>
                    <?= Yii::t('dashboard', '#') ?>
                </th>
                <th>
                    <?= Yii::t('dashboard', 'Person') ?>
                </th>
                <th>
                    <?= Yii::t('dashboard', 'Value') ?>
                </th>
            </tr>
            <?php
            $i = 1;
            foreach ($ranking_productivity as $name => $value) {
                ?>
                <tr class="<?= !empty($member) && ($member->fullname == $name) ? ($value >= $allTheySee ? 'success' : 'warning' ) : '' ?>">
                    <td>
                        <?= $i++ ?>                        
                    </td>                    
                    <td>
                        <?= $name ?>                        
                    </td>
                    <td>
                        <?= $value . '%' ?>
                    </td>
                </tr>
            <?php } ?>

        </table>        
    </div>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
