<?php

use tests\codeception\_pages\HomePage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that new wheel works');

$homePage = HomePage::openBy($I);
$homePage->login('admin', '123456');
$I->see('Logout (admin)');

$I->click('Wheel Questions');

$I->click('Save');

$I->see('success');