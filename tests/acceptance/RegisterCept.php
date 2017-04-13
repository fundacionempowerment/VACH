<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that register works');
$I->amOnPage(Yii::$app->homeUrl);

if (Yii::$app->params['allow_register']) {
    $I->click('Crear cuenta');
    $I->wait(1);

    $I->see('Crear cuenta');

    $I->fillField('RegisterModel[name]', 'Jhon');
    $I->fillField('RegisterModel[surname]', 'Dow');
    $I->fillField('RegisterModel[email]', 'jhon@dow.com');
    $I->fillField('RegisterModel[username]', 'jhon.dow');
    $I->fillField('RegisterModel[password]', '12345678');
    $I->fillField('RegisterModel[confirm]', '12345678');
    $I->click('Crear');
    $I->wait(1);

    $I->see('(jhon.dow)');
} else {
    $I->dontSee('Crear cuenta');
}