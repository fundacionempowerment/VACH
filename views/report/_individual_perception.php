<?php

use yii\helpers\Html;
use app\models\Wheel;
?>
<h3>
    a - Matriz de Percepción, grupal y organizacional
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
<p>
    Estas figuras muestran las diferencias entre cómo me veo (línea roja) y como me ve el grupo (línea verde), en relación a un máximo que se puede conseguir (línea azul o el n° 4 en este caso). En el Informe que arroja la VACH podrán observar los estatus particulares de cada uno de los Integrantes del Equipo, comparando el cómo se ven y la relación de esta creencia con respecto a la realidad que lee el Equipo. A partir de este Delta entre cómo me veo y cómo me ven, surgen las fortalezas y debilidades de cada uno y el grado de integración del individuo con el Grupo.
</p>
<p> 
    La valoración de cada una de las personas del Equipo conforma un promedio del vector, que emerge como fortaleza o debilidad, según la escala que establecimos en nuestra fundamentación. Este ejercicio de hacer emerger el mismo reactivo como fortaleza o debilidad de cada uno, elimina cualquier tipo de configuración subjetiva que el Facilitador pueda prejuzgar y configura todo el esquema de fortalezas, debilidades y zonas críticas que proyectan los individuos, en todos los integrantes del Equipo. Como mencionamos anteriormente, el consenso es Alto cuando es mayor o igual a 3, por lo tanto, el reactivo emergente mayor a dicho consenso, será considerado como una Fortaleza de la persona. Por el contrario, el emergente igual o menor a 2, será considerado como alerta, una Debilidad. Y por último, si la brecha entre cómo ven y cómo me veo es muy superior, será considerado emergente crítico, ya que esto indica que el Evaluado está totalmente ciego de esa realidad que le devuelve el Equipo.
</p>
<h3>
    Análisis
</h3>
<p>
    <?= $report->perception ?>
</p>