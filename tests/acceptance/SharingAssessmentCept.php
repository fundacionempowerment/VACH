<?php

$random = rand(111, 999);
$assessment['name'] = "assessment$random";

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that assessment can be share safely');

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

$I->selectOptionForSelect2('coach_id', 'assisstant');
$I->click('Permitir acceso');
$I->wait(1);

$I->see('Acceso concedido');

$I->logout();

$I->loginAsAssisstant();

$I->see($assessment['name']);

$I->clickMainMenu('Clientes', 'Empresas');
$I->wait(1);

$I->dontSee('ACME');

$I->clickMainMenu('Clientes', 'Personas');
$I->wait(1);

$I->dontSee('Ariel');
$I->dontSee('Beatriz');
$I->dontSee('Carlos');

$I->clickMainMenu('Clientes', 'Equipos');
$I->wait(1);

$I->dontSee('Núcleo');

$I->clickMainMenu('Clientes', 'Relevamientos');
$I->wait(1);

$I->click('Informe');
$I->wait(1);
$I->waitForText('Ariel A');
$I->waitForText('Beatriz B');
$I->waitForText('Carlos C');

$I->clickMainMenu('Clientes', 'Tablero');
$I->wait(1);

$I->selectOptionForSelect2('DashboardFilter[companyId]', 'ACME');
$I->wait(1);
$I->selectOptionForSelect2('DashboardFilter[teamId]', 'Núcleo');
$I->wait(1);
$I->selectOptionForSelect2('DashboardFilter[assessmentId]', $assessment['name']);
$I->wait(1);
$I->selectOptionForSelect2('DashboardFilter[memberId]', 'Carlos C');
$I->wait(1);

$I->logout();

$I->loginAsAdmin();

$I->dontSee($assessment['name']);

$I->clickMainMenu('Clientes', 'Empresas');
$I->wait(1);
$I->dontSee('ACME');

$I->clickMainMenu('Clientes', 'Personas');
$I->wait(1);
$I->dontSee('Ariel');
$I->dontSee('Beatriz');
$I->dontSee('Carlos');

$I->clickMainMenu('Clientes', 'Equipos');
$I->wait(1);
$I->dontSee('Núcleo');

$I->clickMainMenu('Clientes', 'Tablero');
$I->wait(1);

$I->dontSee('ACME');
$I->dontSee('Núcleo');
$I->dontSee($assessment['name']);
$I->dontSee('Carlos C');
