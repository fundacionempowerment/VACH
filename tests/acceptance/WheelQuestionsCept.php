<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that wheel question works');

$I->login('admin', '123456');

$I->see('(admin)');

$I->clickMainMenu('Admin','Preguntas de ruedas');
$I->wait(1);

$I->click('Guardar');
$I->wait(1);

$I->see('guardadas');