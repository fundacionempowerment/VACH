<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that team expansion works');

$I->loginAsCoach();

$I->click('ACME Núcleo Inicial');
$I->wait(1);

$I->selectOptionForSelect2('new_member', 'Dallas');
$I->click('Agregar');
$I->wait(1);

$I->click('Actualizar equipo');
$I->wait(1);

$I->see('Licencias requeridas: 1');

$I->click('Guardar');
$I->wait(1);

$I->see('exitosamente');
$I->wait(1);

$I->seeNumberOfElements("//td[contains(text(), '0%')]", 4 + 4 * 4 * 2);

