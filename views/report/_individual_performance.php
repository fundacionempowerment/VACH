<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    <?= $subtitle_letter ?> - Matriz de Desempeño, grupal y organizacional de <?= $report->member->fullname ?>
</h3>
<?php
if (count($groupPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'data' => $groupPerformanceMatrix,
        'type' => Wheel::TYPE_GROUP,
        'memberId' => $report->member->id,
        'member' => $report->member,
    ]);
}
if (count($organizationalPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'data' => $organizationalPerformanceMatrix,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
        'memberId' => $report->member->id,
        'member' => $report->member,
    ]);
}
?>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/individual_performance') ?>
<h3>
    Análisis
</h3>
<p>
    <?= empty($report->performance) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->performance ?>
</p>