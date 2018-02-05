<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Efectividad Grupal y Organizacional
</h3>
<?php
if (count($groupRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_effectiveness', [
        'team' => $team,
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
    echo $this->render('../dashboard/_effectiveness', [
        'team' => $team,
        'data' => $organizationalRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
        'memberId' => 0,
        'member' => null,
    ]);
}
?>
<p>
    <?= empty($team->report->effectiveness) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $team->report->effectiveness ?>
</p>