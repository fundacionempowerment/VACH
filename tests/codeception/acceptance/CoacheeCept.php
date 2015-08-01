<?php
use tests\codeception\_pages\HomePage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that coachee page works');

$homePage = HomePage::openBy($I);
$homePage->login('admin', '123456');
$I->see('Logout (admin)');
$I->see('Coachees');
$I->see('Ana Trota');
$I->see('Evangelina Soria');
$I->see('Hilda Homonima');

$I->click('New coachee');

$I->fillField('input[name="Coachee[name]"]', 'Mike');
$I->fillField('input[name="Coachee[surname]"]', 'Will');
$I->fillField('input[name="Coachee[email]"]', 'mike@will.com');
$I->click('Save');

$I->see('success');
$I->see('Mike Will');
$I->click('Mike Will');
$I->see('Mike Will');
$I->see('mike@will.com');

$I->click('Coachees');
$I->click('(//a[@title="Delete"])[4]');
$I->see('success');
$I->dontSee('Mike Will');

