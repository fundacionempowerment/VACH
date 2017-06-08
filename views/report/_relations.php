<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
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
<p>
    <?= empty($team->report->relations) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $team->report->relations ?>
</p>