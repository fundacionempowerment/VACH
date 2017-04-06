<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
if (count($groupPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'assessmentId' => $assessment->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'assessmentId' => $assessment->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<p>
    <?= empty($assessment->report->performance) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $assessment->report->performance ?>
</p>