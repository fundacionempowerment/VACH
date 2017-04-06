<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
if (count($groupGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'assessmentId' => $assessment->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'assessmentId' => $assessment->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<p>
    <?= empty($assessment->report->competences) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $assessment->report->competences ?>
</p>