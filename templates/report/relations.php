<?php

use app\models\Wheel;

$levels = [Wheel::TYPE_GROUP, Wheel::TYPE_ORGANIZATIONAL];
$statistic = $team->relationsStatistics;
?>
    <p>
        La matriz de relaciones, tanto grupal como organizacional, corresponde a cómo son inconscientemente las
        proyecciones entre los integrantes del equipo.
    </p>
<?php foreach ($levels as $level) { ?>
    <p><strong><?= $level == Wheel::TYPE_GROUP ?
            Yii::t('dashboard', 'Group Relations Matrix') :
            Yii::t('dashboard', 'Organizational Relations Matrix')
        ?></strong>
    <p>
        <strong>1. Visión cromática (análoga con el semáforo: verde, amarillo y rojo)</strong>
    </p>
    <ul>
        <li>
            Entorno Funcional (verde): <?= Yii::$app->formatter->asPercent($statistic[$level]['green_percentage'], 1) ?>
        </li>
        <li>
            Entorno en alerta (amarillo): <?= Yii::$app->formatter->asPercent($statistic[$level]['yellow_percentage'], 1) ?>
        </li>
        <li>
            Entorno conflictivo (rojo): <?= Yii::$app->formatter->asPercent($statistic[$level]['red_percentage'], 1) ?>
        </li>
    </ul>
    <p>
        <strong>2. El más
            crítico:</strong> <?= $statistic[$level]['most_critic'] ?> <?= Yii::$app->formatter->asPercent($statistic[$level]['most_critic_percentage'], 1) ?>
    </p>
    <p>
        <strong>3. El más
            benévolo:</strong> <?= $statistic[$level]['most_benevolent'] ?> <?= Yii::$app->formatter->asPercent($statistic[$level]['most_benevolent_percentage'], 1) ?>
    </p>
    <p>
        <strong>4. El más y el menos autocrítico:</strong>
    </p>
    <ul>
        <li>
            <?= $statistic[$level]['most_selfcritic'] ?> es el más autocrítico del
            equipo <?= Yii::$app->formatter->asPercent($statistic[$level]['most_selfcritic_percentage'], 1) ?>
        </li>
        <li>
            <?= $statistic[$level]['less_selfcritic'] ?> es el menos autocrítico del
            equipo <?= Yii::$app->formatter->asPercent($statistic[$level]['less_selfcritic_percentage'], 1) ?>
        </li>
    </ul>
    <p>
        <strong>5. El más y el menos productivo según la percepción de sus compañeros:</strong>
    </p>
    <ul>
        <li>
            <?= $statistic[$level]['most_productive'] ?> es el más productivo del
            equipo <?= Yii::$app->formatter->asPercent($statistic[$level]['most_productive_percentage'], 1) ?>
        </li>
        <li>
            <?= $statistic[$level]['less_productive'] ?> es el menos productivo del
            equipo <?= Yii::$app->formatter->asPercent($statistic[$level]['less_productive_percentage'], 1) ?>
        </li>
    </ul>
    <p>
        <strong>6. Cruces interrelacionales:</strong>
    </p>
<?php } ?>