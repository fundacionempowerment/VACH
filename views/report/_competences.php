<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Competencias grupales y organizacionales
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
<?= $this->render('descriptions/competences') ?>
<h3>
    Análisis
</h3>
<p>
    <?= empty($assessment->report->competences) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $assessment->report->competences ?>
</p>