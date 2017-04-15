<?php

$price = rand(100, 300) / 10;
$add = rand(10, 20);
$remove = rand(1, 9);

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that licence management works');

$I->loginAsAdmin();

// Creo nuevo relevamiento

$I->clickMainMenu('Admin', 'Licencias');
$I->wait(1);

$I->click('Agregar licencias');
$I->wait(1);

$I->fillField('AddModel[price]', $price);
$I->fillField('AddModel[quantity]', $add);
$I->selectOptionForSelect2('AddModel[coach_id]', 'coach');
$I->wait(1);

$I->waitForText(Yii::$app->formatter->asCurrency($price * $add));

$I->click('Guardar');
$I->waitForText('exitosamente');

$I->see('Coach');
$I->see($add);
$I->see(Yii::$app->formatter->asCurrency($price));
$I->see(Yii::$app->formatter->asCurrency($price * $add));

$I->click('Quitar licencias');
$I->wait(1);

$I->selectOptionForSelect2('RemoveModel[coach_id]', 'coach');
$I->fillField('RemoveModel[quantity]', $remove);
$I->wait(1);

$I->click('Guardar');
$I->wait(1);
$I->acceptPopup();
$I->waitForText('exitosamente');

$I->see($remove);

$I->logout();

$I->loginAsCoach();

$I->clickMainMenu('(coach)', 'Mis licencias');

$I->waitForText(Yii::$app->formatter->asCurrency($price));
$I->see(Yii::$app->formatter->asCurrency($price * $add));
$I->see($add);
$I->see($remove);

$I->see(100 + $add - $remove);

