<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    Matrices de Desempeño Grupal y Organizacional
</h3>
<?php
if (count($groupPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'data' => $groupPerformanceMatrix,
        'members' => $members,
        'type' => Wheel::TYPE_GROUP,
        'memberId' => 0,
    ]);
}
?>
<?php
if (count($organizationalPerformanceMatrix) > 0) {
    echo $this->render('../dashboard/_matrix', [
        'data' => $organizationalPerformanceMatrix,
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
    Los datos contenidos en la matriz de Efectividad los hemos representado gráficamente a través de lo que hemos dado en denominar Matriz de Desempeño. 
</p>
<p>
    En el eje horizontal hemos representado la Productividad, expresada en %. El punto de corte, es decir lo que indicará que un individuo tenga productividad Alta o Baja, será la Media Aritmética de todas las Productividades. Valores mayores a ésta determinarán una productividad Alta y valores menores a ella, una productividad Baja. 
</p>
<p>
    En el eje vertical ha sido representado el Grado de Conciencia de cada persona, esto es la brecha entre “cómo me ven” y “cómo me veo”. Recordemos que ésta puede ser positiva (cuando me ven que doy más de lo que yo creo que doy) o negativa (cuando me ven que doy menos de lo que yo creo que doy). Lo que va a determinar si mi Grado de conciencia es Alto o Bajo va a ser la Desviación Estándar. Si el Grado de conciencia es menor a la Desviación Estándar, determinará un Alto Grado de Conciencia (ya sea positivo o negativo). En caso contrario, es decir que el grado de conciencia obtenido sea mayor a la Desviación estándar, determinará un Grado de Conciencia Bajo (también en este caso, podrá ser positivo o negativo). 
</p>
<p>
    Si traducimos esto en el gráfico, observamos que en él han quedado determinadas ocho zonas, que pasamos a detallar: 
</p>
<table class="table table-bordered">
    <tr>
        <td>
            Baja productividad-Baja conciencia (+)
        </td>
        <td>
            Alta productividad-Baja conciencia (+)
        </td>
    </tr>
    <tr>
        <td>
            Baja productividad-Alta conciencia (+)
        </td>
        <td>
            Alta productividad-Alta conciencia (+)
        </td>
    </tr>
    <tr>
        <td>
            Baja productividad-Alta conciencia (-)
        </td>
        <td>
            Alta productividad-Alta conciencia (-)
        </td>
    </tr>
    <tr>
        <td>
            Baja productividad-Baja conciencia (-)
        </td>
        <td>
            Alta productividad-Baja conciencia (-)
        </td>
    </tr>
</table>

<h3>
    Análisis
</h3>
<p>
    <?= $assessment->report->performance ?>
</p>