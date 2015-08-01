<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that register works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Empowerment');
$I->see('Login');

$I->click('Sign up');
$I->fillField('input[name="RegisterModel[name]"]', 'Jhon');
$I->fillField('input[name="RegisterModel[surname]"]', 'Dow');
$I->fillField('input[name="RegisterModel[email]"]', 'jhon@dow.com');
$I->fillField('input[name="RegisterModel[username]"]', 'jhon.dow');
$I->fillField('input[name="RegisterModel[password]"]', '12345678');
$I->fillField('input[name="RegisterModel[confirm]"]', '12345678');
$I->checkOption('I\'m coach');
$I->click('Register');
$I->see('Logout (jhon.dow)');
