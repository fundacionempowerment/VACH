<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that wheel question works');

$I->loginAsAdmin();

$I->clickMainMenu('Admin','Tipos de equipos');
$I->wait(1);

$I->click("//*[@title = 'Edit']");
$I->click("Guardar");
$I->wait(1);

$I->see('Empresa ha sido exitosamente editado');