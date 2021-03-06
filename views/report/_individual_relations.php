<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<?php
if (count($groupRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation_graph', [
        'teamId' => $team->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}
if (count($organizationalRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation_graph', [
        'teamId' => $team->id,
        'memberId' => $report->member->id,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<p>
    <?= empty($report->relations) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $report->relations ?>
</p>