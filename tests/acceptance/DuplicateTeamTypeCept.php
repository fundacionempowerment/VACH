<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that wheel question works');

$I->loginAsAdmin();

$I->clickMainMenu('Admin','Tipos de equipos');
$I->wait(1);

$I->click("Duplicar");
$I->acceptPopup();

$I->wait(1);

$I->see('(copy)');