<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Desempeño Grupal y Organizacional
</h3>
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
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/performance') ?>
<h3>
    Análisis
</h3>
<p>
    <?= empty($assessment->report->performance) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $assessment->report->performance ?>
</p>