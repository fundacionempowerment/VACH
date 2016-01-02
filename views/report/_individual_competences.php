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
        'gauges' => $groupGauges,
        'type' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'gauges' => $organizationalGauges,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
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
    <?= $report->competences ?>
</p>