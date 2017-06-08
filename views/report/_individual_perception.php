<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
    if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0) {
        echo $this->render('../dashboard/_lineal', [
            'teamId' => $team->id,
            'memberId' => $report->member->id,
            'wheelType' => Wheel::TYPE_GROUP,
        ]);
    }
    if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0) {
        echo $this->render('../dashboard/_lineal', [
            'teamId' => $team->id,
            'memberId' => $report->member->id,
            'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
        ]);
    }
?>
<div class="clearfix"></div>
<p>
    <?= empty($report->perception) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->perception ?>
</p>