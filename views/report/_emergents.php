<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
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
<p>
    <?= empty($assessment->report->emergents) ? Yii::t('report', 'Since graphic clarity, farther analisis is not required') : $assessment->report->emergents ?>
</p>