<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    <?= $subtitle_letter ?> - Matriz de Competencias grupales y organizacionales de <?= $report->member->fullname ?>
</h3>
<?php
if (count($groupGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'assessmentId' => $assessment->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'assessmentId' => $assessment->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/individual_competences') ?>
<h3>
    Análisis
</h3>
<p>
    <?= empty($report->competences) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->competences ?>
</p>