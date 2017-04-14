<?php

$random = rand(111, 999);
$assessment['name'] = "assessment$random";

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that assessment crud works');

$I->loginAsCoach();

// Creo nuevo relevamiento

$I->clickMainMenu('Clientes', 'Equipos');
$I->wait(1);

$I->click('ACME Núcleo');
$I->wait(2);

$I->click('Nuevo relevamiento');
$I->wait(1);

$I->see('Licencias requeridas: 3');

$I->fillField('Assessment[name]', $assessment['name']);

$I->click('Guardar');
$I->wait(1);

$I->click('Inicio');
$I->wait(1);

$I->see($assessment['name']);

$I->click($assessment['name']);
$I->wait(1);

$I->click('ACME Núcleo');
$I->wait(1);

$I->click('(//a[@title="Eliminar"])[3]');
$I->wait(1);

$I->click('Eliminar');
$I->wait(1);

$I->dontSee($assessment['name']);
