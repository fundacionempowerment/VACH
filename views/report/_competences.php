<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    4 - Matrices de Competencias grupales y organizacionales
</h3>
<?php
if (count($groupGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'gauges' => $groupGauges,
        'type' => Wheel::TYPE_GROUP,
    ]);
}
?>
<?php
if (count($organizationalGauges) > 0) {
    echo $this->render('../dashboard/_gauges', [
        'gauges' => $organizationalGauges,
        'type' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}
?>
<h3>
    Descripción
</h3>
<p>
    Los datos contenidos en este Gráfico son en función de las Competencias medidas, tanto en el ámbito grupal como organizacional. Cada una de estas Competencias emergen como débiles o fuertes, según cómo hayan sido evaluados los integrantes del Equipo por el resto de sus pares, en relación a la Competencia en cuestión. Por ejemplo, si cada uno de los integrantes del Equipo obtiene un puntaje bajo en Flexibilidad, entonces la Flexibilidad será una debilidad grupal que 
    el Equipo deberá fortalecer. 
</p>
<p>
    Esta lectura también puede hacerse extensiva a los integrantes del Equipo, que viendo sus casos particulares, podemos observar las Competencias que cada uno mejor ha desarrollado y cuáles quedan aún por trabajar, tal cual lo muestra la Matriz.
</p>
<p>
    A partir del análisis de estos datos se pueden establecer los perfiles de “liderazgo” propios de cada uno y del Equipo; con el objeto de establecer un plan de capacitación y entrenamiento para fortalecer aquellas Competencias que aún emergen como débiles.
</p>
<h3>
    Análisis
</h3>
<p>
    <?= $assessment->report->competences ?>
</p>