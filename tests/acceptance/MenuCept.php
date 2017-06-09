<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that main menu works in every item');

$I->loginAsCoach();

$I->checkMenuItem('Inicio');

$I->checkMenuItem('Clientes', 'Empresas');
$I->checkMenuItem('Clientes', 'Personas');
$I->checkMenuItem('Clientes', 'Equipos');
$I->checkMenuItem('Clientes', 'Tablero');

$I->checkMenuItem('Ayuda', 'Historial de eventos');

$I->checkMenuItem('(coach)', 'Mis datos');
$I->checkMenuItem('(coach)', 'Mis licencias');
$I->checkMenuItem('(coach)', 'Mis pagos');

$I->logout();
$I->loginAsAssisstant();

$I->checkMenuItem('Inicio');

$I->checkMenuItem('Clientes', 'Empresas');
$I->checkMenuItem('Clientes', 'Personas');
$I->checkMenuItem('Clientes', 'Equipos');
$I->checkMenuItem('Clientes', 'Tablero');

$I->checkMenuItem('Ayuda', 'Historial de eventos');

$I->checkMenuItem('(assisstant)', 'Mis datos');
$I->checkMenuItem('(assisstant)', 'Mis licencias');
$I->checkMenuItem('(assisstant)', 'Mis pagos');

$I->logout();
$I->loginAsAdmin();

$I->checkMenuItem('Inicio');

$I->checkMenuItem('Clientes', 'Empresas');
$I->checkMenuItem('Clientes', 'Personas');
$I->checkMenuItem('Clientes', 'Equipos');
$I->checkMenuItem('Clientes', 'Tablero');

$I->checkMenuItem('Ayuda', 'Historial de eventos');

$I->checkMenuItem('Admin', 'Licencias');
$I->checkMenuItem('Admin', 'Pagos');
$I->checkMenuItem('Admin', 'Usuario');
$I->checkMenuItem('Admin', 'Preguntas de ruedas');
$I->checkMenuItem('Admin', 'Comentarios');

$I->checkMenuItem('(admin)', 'Mis datos');
$I->checkMenuItem('(admin)', 'Mis licencias');
$I->checkMenuItem('(admin)', 'Mis pagos');