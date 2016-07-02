<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Efectividad Grupal y Organizacional
</h3>
<?php
if (count($groupRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_number_matrix', [
        'assessment' => $assessment,
        'data' => $groupRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_GROUP,
        'memberId' => 0,
        'member' => null,
    ]);
}
?>
<?php
if (count($organizationalRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_number_matrix', [
        'assessment' => $assessment,
        'data' => $organizationalRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
        'memberId' => 0,
        'member' => null,
    ]);
}
?>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/effectiveness') ?>
<h3>
    Análisis
</h3>
<p>
    <?= $assessment->report->effectiveness ?>
</p>