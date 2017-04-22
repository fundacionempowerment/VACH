<?php

$random = rand(1111, 9999);
$password = "password$random";

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that login works');

$I->amGoingTo('try to login with empty credentials');
$I->login('', '');
$I->see('Nombre usuario no puede estar vacío.');
$I->see('Contraseña no puede estar vacío.');

$I->amGoingTo('try to login with wrong credentials');
$I->login('admin', 'wrong');
$I->see('Nombre de usuario o contraseña incorrectos');

$I->amGoingTo('try to login with wrong wheel token');
$I->fillField('Wheel[token]', rand(1111, 9999));
$I->click('Ejecutar');
$I->wait(1);
$I->see('Rueda no encontrada.');

$I->amGoingTo('try to login with correct credentials');
$I->login('admin', '123456');
$I->see('(admin)');

$I->clickMainMenu('(admin)', 'Mis datos');
$I->wait(1);

$I->fillField('User[password]', $password);
$I->fillField('User[password_confirm]', $password);

$I->click('Guardar');
$I->wait(1);

$I->see('éxito');

$I->clickMainMenu('(admin)', 'Salir');
$I->wait(1);

$I->login('admin', $password);

$I->see('(admin)');