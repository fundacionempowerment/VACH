<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    d - Matriz de Emergentes grupales y organizacionales
</h3>
<?php
if (count($groupEmergents) > 0) {
    echo $this->render('../dashboard/_emergents', [
        'emergents' => $groupEmergents,
        'type' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalEmergents) > 0) {
    echo $this->render('../dashboard/_emergents', [
        'emergents' => $organizationalEmergents,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<h3>
    Análisis
</h3>
<p>
    <?= $report->emergents ?>
</p>