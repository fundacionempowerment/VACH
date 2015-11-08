<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Emergentes grupales y organizacionales
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
    Descripción
</h3>
<p>
    Al igual que la Matriz de Competencias, esta Matriz posee una misma lógica: Cada Competencia está compuesta por ocho preguntas que contienen su significado. 
</p>
<p>
    Científicamente estas preguntas se llaman «Reactivos» o «Vectores», que son asignados con valores y por consiguiente promedian el resultado final de la Competencia en cuestión. Como decíamos, si estos reactivos emergen de la misma manera que en la Matriz de Competencias, el reactivo emergente será aquel que se repita por sobre la media del Equipo, de la sumatoria de las fortalezas y debilidades individuales de cada uno. 

</p>
<p>
    Es importante advertir que estos reactivos mostrarán los objetivos específicos que se deberán abordar en el Grupo para saber dónde sostener las Competencias que emergieron como las más fuertes, y para trabajar las Competencias que emergieron como las más débiles. 
</p>
<h3>
    Análisis
</h3>
<p>
    <?= $assessment->report->emergents ?>
</p>