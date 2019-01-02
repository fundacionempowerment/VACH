<?php

?>
<div class="preview-result">
    <?= $this->render('_preview_persons', ['persons' => $persons]) ?>
    <?= $this->render('_preview_companies', ['companies' => $companies]) ?>
    <?= $this->render('_preview_teams', ['teams' => $teams, 'teamInvitations' => $teamInvitations]) ?>
    <?= $this->render('_preview_stocks', ['stocks' => $stocks]) ?>
    <?= $this->render('_preview_payments', ['payments' => $payments]) ?>
</div>