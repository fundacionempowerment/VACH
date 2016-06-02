<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Emergentes grupales y organizacionales
</h3>
<?php
if (count($groupEmergents) > 0) {
    echo $this->render('../dashboard/_emergents', [
        'emergents' => $groupEmergents,
        'type' => Wheel::TYPE_GROUP,
        'memberId' => 0,
    ]);
}
?>
<?php
if (count($organizationalEmergents) > 0) {
    echo $this->render('../dashboard/_emergents', [
        'emergents' => $organizationalEmergents,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
        'memberId' => 0,
    ]);
}
?>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/emergents') ?>
<h3>
    Análisis
</h3>
<p>
    <?= $assessment->report->emergents ?>
</p>