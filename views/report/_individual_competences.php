<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
if (count($groupGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'teamId' => $team->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'teamId' => $team->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<p>
    <?= empty($report->competences) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->competences ?>
</p>