<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    <?= $subtitle_letter ?> - Matriz de Relaciones grupales y organizacionales de <?= $report->member->fullname ?>
</h3>
<?php
if (count($groupRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation', [
        'data' => $groupRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_GROUP,
        'memberId' => $report->member->id,
        'member' => $report->member,
    ]);
}
if (count($organizationalRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation', [
        'data' => $organizationalRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
        'memberId' => $report->member->id,
        'member' => $report->member,
    ]);
}
?>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/individual_relations') ?>
<h3>
    Análisis
</h3>
<p>
    <?= empty($report->relations) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->relations ?>
</p>