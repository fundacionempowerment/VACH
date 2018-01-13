<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that wheel question works');

$I->loginAsAdmin();

$I->clickMainMenu('Admin','Preguntas de ruedas');
$I->wait(1);

$I->click('Guardar');
$I->wait(1);

$I->see('guardadas');