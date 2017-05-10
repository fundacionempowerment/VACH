<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
if (count($groupPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'teamId' => $team->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'teamId' => $team->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<p>
    <?= empty($team->report->performance) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $team->report->performance ?>
</p>