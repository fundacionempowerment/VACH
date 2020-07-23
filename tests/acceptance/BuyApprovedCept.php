<?php

return;

$email = 'coach' . rand(11111, 99999) . "@example.com";
$price = 18;
$add = rand(10, 20);


$I = new AcceptanceTester($scenario);

$I->wantTo('ensure that licence buying works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

$I->clickMainMenu('(coach)', 'Mis datos');
$I->wait(1);

$I->fillField('User[email]', $email);

$I->click('Guardar');
$I->wait(1);

$I->clickMainMenu('(coach)', 'Mis licencias');
$I->wait(1);

$I->click('Comprar licencias');
$I->wait(1);

$I->fillField('BuyModel[quantity]', $add);
$I->wait(1);

$I->click('Comenzar pago');

$I->waitForText('Selecciona el medio de pago', 60);
$I->waitForText((string) Yii::$app->formatter->asDecimal($price * $add), 30);

$I->click(".//*[@id='pm-VISA']");

$I->fillField(".//*[@id='ccNumber']", '4539786601080019');
$I->wait(1);
$I->fillField(".//*[@id='expirationDateMonth']", '11');
$I->wait(1);
$I->fillField(".//*[@id='expirationDateYear']", '22');
$I->wait(1);
$I->fillField(".//*[@id='securityCode']", '123');
$I->wait(1);
$I->fillField(".//*[@id='cc_fullName']", 'APPROVED');
$I->wait(1);
$I->fillField(".//*[@id='cc_dniNumber']", '12345678');
$I->wait(1);
$I->fillField(".//*[@id='contactPhone']", '26156789456');
$I->wait(1);
$I->fillField(".//*[@id='cc_street1']", 'San Martín 123');
$I->wait(1);
$I->fillField(".//*[@id='cc_city']", 'Mendoza');
$I->wait(1);

$I->click(".//*[@id='buyer_data_button_pay']");
$I->wait(2);

$I->waitForText('Tu transacción ha sido aprobada');
$I->wait(2);

$I->click(".//*[@id='response_button_continue']");
$I->wait(2);

$I->waitForText('Pago exitoso', 150);
