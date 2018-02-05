<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
if (count($groupCompetences) > 0) {
    echo $this->render('../dashboard/_competences', [
        'teamId' => $team->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalCompetences) > 0) {
    echo $this->render('../dashboard/_competences', [
        'teamId' => $team->id,
        'memberId' => 0,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<p>
    <?= empty($team->report->competences) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $team->report->competences ?>
</p>