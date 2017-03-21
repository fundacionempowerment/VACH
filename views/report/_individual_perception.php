<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    <?= $subtitle_letter ?> - Matriz de Percepción, grupal y organizacional de <?= $report->member->fullname ?>
</h3>
<?php
    if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0) {
        echo $this->render('../dashboard/_lineal', [
            'assessmentId' => $assessment->id,
            'memberId' => $report->member->id,
            'wheelType' => Wheel::TYPE_GROUP,
        ]);
    }
    if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0) {
        echo $this->render('../dashboard/_lineal', [
            'assessmentId' => $assessment->id,
            'memberId' => $report->member->id,
            'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
        ]);
    }
?>
<div class="clearfix"></div>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/individual_perception') ?>
<h3>
    Análisis
</h3>
<p>
    <?= empty($report->perception) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->perception ?>
</p>