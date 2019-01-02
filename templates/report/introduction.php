<?php
/**
 * @var \app\models\Team $team
 */
?>
<p>
    El equipo a cargo de <?= $team->sponsor->fullname ?> queda definido por:
</p>
<ul>
    <?php foreach ($team->members as $teamMember) { ?>
        <li><?= $teamMember->member->fullname ?></li>
    <?php } ?>
</ul>
<p>
    <strong>Ubicación del equipo dentro del organigrama</strong>
</p>
<p>
    <strong>Fortalezas del equipo</strong>
</p>
<p>
    <strong>Debilidades del equipo</strong>
</p>
<p>
    <strong>Experiencias de capacitación anteriores</strong>
</p>
<p>
    <strong>Breve historial de la origanización</strong>
</p>
<p>
    <strong>Expectativas del patrocinador</strong>
</p>
<p>
    <strong>Objetivos del taller</strong>
</p>
