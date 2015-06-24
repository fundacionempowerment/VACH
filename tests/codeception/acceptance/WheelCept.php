<?php

use tests\codeception\_pages\HomePage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that new wheel works');

$homePage = HomePage::openBy($I);
$homePage->login('admin', '123456');
$I->see('Logout (admin)');

$I->click('Hilda Homonima');
$I->see('Personal data');
$I->see('Wheels');

$I->click('New wheel');

$I->see('Tiempo libre', 'h3');
$I->selectOption('form input[name="answer0"]', 0);
$I->selectOption('form input[name="answer1"]', 0);
$I->selectOption('form input[name="answer2"]', 0);
$I->selectOption('form input[name="answer3"]', 0);
$I->selectOption('form input[name="answer4"]', 0);
$I->selectOption('form input[name="answer5"]', 0);
$I->selectOption('form input[name="answer6"]', 0);
$I->selectOption('form input[name="answer7"]', 0);
$I->selectOption('form input[name="answer8"]', 0);
$I->selectOption('form input[name="answer9"]', 0);
$I->click('Save and next dimension...');

$I->see('Trabajo', 'h3');
$I->selectOption('form input[name="answer10"]', 1);
$I->selectOption('form input[name="answer11"]', 1);
$I->selectOption('form input[name="answer12"]', 1);
$I->selectOption('form input[name="answer13"]', 1);
$I->selectOption('form input[name="answer14"]', 1);
$I->selectOption('form input[name="answer15"]', 1);
$I->selectOption('form input[name="answer16"]', 1);
$I->selectOption('form input[name="answer17"]', 1);
$I->selectOption('form input[name="answer18"]', 1);
$I->selectOption('form input[name="answer19"]', 1);
$I->click('Save and next dimension...');

$I->see('Familia', 'h3');
$I->selectOption('form input[name="answer20"]', 2);
$I->selectOption('form input[name="answer21"]', 2);
$I->selectOption('form input[name="answer22"]', 2);
$I->selectOption('form input[name="answer23"]', 2);
$I->selectOption('form input[name="answer24"]', 2);
$I->selectOption('form input[name="answer25"]', 2);
$I->selectOption('form input[name="answer26"]', 2);
$I->selectOption('form input[name="answer27"]', 2);
$I->selectOption('form input[name="answer28"]', 2);
$I->selectOption('form input[name="answer29"]', 2);
$I->click('Save and next dimension...');

$I->click('Hilda Homonima');
$I->see('continue...');
$I->click('continue...');
$I->click('Save and next dimension...');
$I->click('Save and next dimension...');
$I->click('Save and next dimension...');

$I->see('Dimensión física');
$I->selectOption('form input[name="answer30"]', 3);
$I->selectOption('form input[name="answer31"]', 3);
$I->selectOption('form input[name="answer32"]', 3);
$I->selectOption('form input[name="answer33"]', 3);
$I->selectOption('form input[name="answer34"]', 3);
$I->selectOption('form input[name="answer35"]', 3);
$I->selectOption('form input[name="answer36"]', 3);
$I->selectOption('form input[name="answer37"]', 3);
$I->selectOption('form input[name="answer38"]', 3);
$I->selectOption('form input[name="answer39"]', 3);
$I->click('Save and next dimension...');

$I->see('Dimensión emocional');
$I->selectOption('form input[name="answer40"]', 4);
$I->selectOption('form input[name="answer41"]', 4);
$I->selectOption('form input[name="answer42"]', 4);
$I->selectOption('form input[name="answer43"]', 4);
$I->selectOption('form input[name="answer44"]', 4);
$I->selectOption('form input[name="answer45"]', 4);
$I->selectOption('form input[name="answer46"]', 4);
$I->selectOption('form input[name="answer47"]', 4);
$I->selectOption('form input[name="answer48"]', 4);
$I->selectOption('form input[name="answer49"]', 4);
$I->click('Save and next dimension...');

$I->see('Dimensión mental');
$I->selectOption('form input[name="answer50"]', 0);
$I->selectOption('form input[name="answer51"]', 1);
$I->selectOption('form input[name="answer52"]', 2);
$I->selectOption('form input[name="answer53"]', 3);
$I->selectOption('form input[name="answer54"]', 4);
$I->selectOption('form input[name="answer55"]', 0);
$I->selectOption('form input[name="answer56"]', 1);
$I->selectOption('form input[name="answer57"]', 2);
$I->selectOption('form input[name="answer58"]', 3);
$I->selectOption('form input[name="answer59"]', 4);
$I->click('Save and next dimension...');

$I->see('Dimensión existencial');
$I->selectOption('form input[name="answer60"]', 4);
$I->selectOption('form input[name="answer61"]', 1);
$I->selectOption('form input[name="answer62"]', 2);
$I->selectOption('form input[name="answer63"]', 3);
$I->selectOption('form input[name="answer64"]', 4);
$I->selectOption('form input[name="answer65"]', 4);
$I->selectOption('form input[name="answer66"]', 1);
$I->selectOption('form input[name="answer67"]', 2);
$I->selectOption('form input[name="answer68"]', 3);
$I->selectOption('form input[name="answer69"]', 4);
$I->click('Save and next dimension...');

$I->see('Dimensión espiritual');
$I->selectOption('form input[name="answer70"]', 3);
$I->selectOption('form input[name="answer71"]', 4);
$I->selectOption('form input[name="answer72"]', 3);
$I->selectOption('form input[name="answer73"]', 4);
$I->selectOption('form input[name="answer74"]', 3);
$I->selectOption('form input[name="answer75"]', 4);
$I->selectOption('form input[name="answer76"]', 3);
$I->selectOption('form input[name="answer77"]', 4);
$I->selectOption('form input[name="answer78"]', 3);
$I->selectOption('form input[name="answer79"]', 4);
$I->click('Save and finish');

$I->see('Tiempo libre: 0');
$I->see('Trabajo: 1');
$I->see('Familia: 2');
$I->see('D. física: 3');
$I->see('D. emocional: 4');
$I->see('D. mental: 2');
$I->see('D. existencial: 2.8');
$I->see('D. espiritual: 3.5');










