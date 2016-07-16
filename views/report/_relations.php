<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Relaciones Grupal y Organizacional
</h3>
<h3><?= Yii::t('dashboard', 'Group Relations Matrix') ?></h3>
<?php
if (count($groupRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation_table', [
        'data' => $groupRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_GROUP,
        'memberId' => 0,
    ]);
}
?>
<h3><?= Yii::t('dashboard', 'Organizational Relations Matrix') ?></h3>
<?php
if (count($organizationalRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation_table', [
        'data' => $organizationalRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
        'memberId' => 0,
    ]);
}
?>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/relations') ?>
<h3>
    Análisis
</h3>
<p>
    <?= empty($assessment->report->relations) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $assessment->report->relations ?>
</p>