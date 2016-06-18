<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    <?= $subtitle_letter ?> - Matriz de Emergentes grupales y organizacionales de <?= $report->member->fullname ?>
</h3>
<div class="col-md-6">
    <?php
    if (count($groupEmergents) > 0) {
        echo $this->render('../dashboard/_emergents', [
            'emergents' => $groupEmergents,
            'type' => Wheel::TYPE_GROUP,
            'memberId' => $report->member->id,
        ]);
    }
    ?>
</div>
<div class="col-md-6">
    <?php
    if (count($organizationalEmergents) > 0) {
        echo $this->render('../dashboard/_emergents', [
            'emergents' => $organizationalEmergents,
            'type' => Wheel::TYPE_ORGANIZATIONAL,
            'memberId' => $report->member->id,
        ]);
    }
    ?>
</div>
<div class="clearfix"></div>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/individual_emergents') ?>
<h3>
    Análisis
</h3>
<p>
    <?= $report->emergents ?>
</p>