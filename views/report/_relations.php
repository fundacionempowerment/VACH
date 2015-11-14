<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Relaciones Grupal y Organizacional
</h3>
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
<h3>
    Descripción
</h3>
<p>
    La Matriz inserta en este ítem corresponde a cómo son —inconscientemente— las proyecciones, en cuanto a las relaciones interpersonales del Grupo: ¿Cómo funciona su lectura e interpretación? Como primera lectura general, si vamos por ejemplo al Estatus Grupal, podemos observar que cada una de las personas ubicadas en la columna izquierda asigna un valor para cada una de las competencias de los demás integrantes del equipo; de todos estos valores se saca el promedio y ese número es que el que se observa en la tabla. Es decir que ese valor refleja el promedio de cómo percibió cada a integrante a sus pares, por ejemplo en las ocho Competencias grupales, y lo mismo será para el Estatus Organizacional. Otra observación que se lee del gráfico es que hay filas resaltadasen color, a los efectos de establecer el mayor nivel de crítica al momento de evaluar; no por alguna situación en particular, sino sencillamente por un grado de subjetivismo que opera todo el tiempo: hay personas que sencillamente son más exigentes que otras per se. Esto nos permite analizar aquellas Proyecciones que se juegan por debajo de ese máximo nivel de crítica, que como tal, se muestran en el gráfico como muy significativas.
</p>
<h3>
    Análisis
</h3>
<p>
    <?= $assessment->report->relations ?>
</p>