<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<div class="col-xs-6">
    <?php
    if (count($groupEmergents) > 0) {
        echo $this->render('../dashboard/_emergents', [
            'emergents' => $groupEmergents,
            'type' => Wheel::TYPE_GROUP,
            'memberId' => $report->member->id,
            'member' => $report->member,
        ]);
    }
    ?>
</div>
<div class="col-xs-6">
    <?php
    if (count($organizationalEmergents) > 0) {
        echo $this->render('../dashboard/_emergents', [
            'emergents' => $organizationalEmergents,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
            'memberId' => $report->member->id,
            'member' => $report->member,
        ]);
    }
    ?>
</div>
<div class="clearfix"></div>
<p>
    <?= empty($report->emergents) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->emergents ?>
</p>