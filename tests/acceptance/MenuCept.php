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
$I->checkMenuItem('(coach)', 'Mi contraseña');
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
$I->checkMenuItem('(assisstant)', 'Mi contraseña');
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

$I->checkMenuItem('Admin', 'Usuarios');
$I->checkMenuItem('Admin', 'Licencias');
$I->checkMenuItem('Admin', 'Pagos');
$I->checkMenuItem('Admin', 'Liquidaciones');
//$I->checkMenuItem('Admin', 'Empresas');
//$I->checkMenuItem('Admin', 'Personas');
//$I->checkMenuItem('Admin', 'Equipos');
$I->checkMenuItem('Admin', 'Tipos de equipos');
$I->checkMenuItem('Admin', 'Tipos de licencias');
$I->checkMenuItem('Admin', 'Valórenos');

$I->checkMenuItem('(admin)', 'Mis datos');
$I->checkMenuItem('(admin)', 'Mi contraseña');
$I->checkMenuItem('(admin)', 'Mis licencias');
$I->checkMenuItem('(admin)', 'Mis pagos');