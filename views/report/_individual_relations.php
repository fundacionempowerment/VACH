<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    b - Matriz de Relaciones grupales y organizacionales
</h3>
<?php
if (count($groupRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation', [
        'data' => $groupRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_GROUP,
        'memberId' => $report->member->id,
    ]);
}
if (count($organizationalRelationsMatrix) > 0) {
    echo $this->render('../dashboard/_relation', [
        'data' => $organizationalRelationsMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
        'memberId' => $report->member->id,
    ]);
}
?>
<h3>
    Descripción
</h3>
<p>
    La Matriz inserta en este ítem corresponde a cómo son —inconscientemente— las proyecciones, en cuanto a las relaciones interpersonales del Grupo: ¿Cómo funciona su lectura e interpretación? Cada uno de los integrantes estará situado en el centro del grupo. Y de él salen flechas hacia los otros integrantes, que podrán ser de tres colores, según sea la relación establecida, esto es, qué le proyecta esta persona a los demás integrantes.</p>
<p> 
    Si el valor promedio es inferior a 40% se establece una Relación Conflictiva (rojo); si es mayor a 40% y menor a 70% hay una relación Normal (amarillo), y si es superior a 70% hay una relación Buena (verde).
</p>
<h3>
    Análisis
</h3>
<p>
    <?= $report->relations ?>
</p>