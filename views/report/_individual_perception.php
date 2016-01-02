<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    <?= $subtitle_letter ?> - Matriz de Percepción, grupal y organizacional de <?= $report->member->fullname ?>
</h3>
<?php
if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0)
    echo $this->render('../dashboard/_lineal', [
        'title' => Yii::t('dashboard', 'Group Perception Matrix'),
        'wheel' => $reflectedGroupWheel,
        'wheelName' => Yii::t('dashboard', 'How they see me'),
        'comparedWheel' => $projectedGroupWheel,
        'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
        'type' => Wheel::TYPE_GROUP,
    ]);
if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0)
    echo $this->render('../dashboard/_lineal', [
        'title' => Yii::t('dashboard', 'Organizational Perception Matrix'),
        'wheel' => $reflectedOrganizationalWheel,
        'wheelName' => Yii::t('dashboard', 'How they see me'),
        'comparedWheel' => $projectedOrganizationalWheel,
        'comparedWheelName' => Yii::t('dashboard', 'How I see me'),
        'type' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
?>
<h3>
    Descripción
</h3>
<?= $this->render('descriptions/individual_perception') ?>
<h3>
    Análisis
</h3>
<p>
    <?= $report->perception ?>
</p>