<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
if (count($groupPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_performance', [
        'teamId' => $team->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
if (count($organizationalPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_performance', [
        'teamId' => $team->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<p>
    <?= empty($report->performance) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->performance ?>
</p>