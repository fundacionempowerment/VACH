<?php

use tests\codeception\_pages\HomePage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that full assessment works');

$homePage = HomePage::openBy($I);
$I->click('English');
$I->wait(1);
$homePage->login('coach.coach', '123456');
$I->wait(1);
$I->see('Logout (coach.coach)');
$I->wait(1);

// Create a company
$I->click('Clients');
$I->click('Companies');

$I->see('Companies', 'h1');

$I->click('New company');

$I->fillField('Company[name]', 'ACME');
$I->fillField('Company[email]', 'acme@fake.com');
$I->fillField('Company[phone]', '(123) 456-1234');
$I->click('Save');
$I->wait(1);

$I->see('success');

// Create a host
$I->click('Clients');
$I->click('Persons');

$I->see('Persons', 'h1');

$I->click('New person');

$I->fillField('Person[name]', 'Jhon');
$I->fillField('Person[surname]', 'Smith');
$I->fillField('Person[email]', 'jhon@fake.com');
$I->fillField('Person[phone]', '(123) 456-1234');
$I->click('Save');
$I->wait(1);

$I->see('success');

// Create a team

$I->click('Clients');
$I->click('Teams');

$I->see('Teams', 'h1');

$I->click('New team');

$I->fillField('Team[name]', 'Dream');
$I->click('Save');
$I->wait(1);

$I->see('success');

// Add team members

$team_size = 3;
$wheel_tokens = [];

for ($i = 1; $i <= $team_size; $i++) {
    $I->click('New member');

    $I->fillField('Person[name]', "Mark $i");
    $I->fillField('Person[surname]', 'Dow');
    $I->fillField('Person[email]', "mark$i@fake.com");
    $I->click('Save');
    $I->wait(1);

    $I->see('success');
}

// Create an assessment

$I->click('New assessment');
$I->click('Save');
$I->wait(1);

// grab all tokens

for ($i = 0; $i < $team_size * 3; $i++) {
    $I->click('#cell_' . $i);
    $I->wait(1);
    $wheelToken[] = $I->grabTextFrom('#token');
    $I->click("//button[@class='close']");
    $I->wait(1);
}

// Prepare to fill wheels

$I->click('Logout');
$I->click('English');
$I->wait(1);

// Fill individual wheels
for ($i = 0; $i < $team_size; $i++) {
    $tokens = split('-', $wheelToken[$i]);

    $I->fillField('token1', $tokens[0]);
    $I->fillField('token2', $tokens[1]);
    $I->fillField('token3', $tokens[2]);
    $I->wait(1);
    $I->click('Run');
    $I->wait(1);

    $I->see('Mark ' . ($i + 1));

    $I->click('Begin');
    $I->wait(1);

    for ($d = 0; $d < 8; $d++) {
        for ($q = 0; $q < 10; $q++) {
            $answer = 'answer' . (($d * 10) + $q);
            $random = rand(0, 4);
            $I->selectOption("form input[name=$answer]", $random);
        }

        if ($d < 7)
            $I->click('Save and next dimension...');
        else
            $I->click('Save');
        $I->wait(1);
    }

    $I->see('success');

    $I->click('Home');
}


// Fill group and organizational wheels

for ($i = 3; $i < $team_size * 3; $i++) {
    $tokens = split('-', $wheelToken[$i]);

    $I->fillField('token1', $tokens[0]);
    $I->fillField('token2', $tokens[1]);
    $I->fillField('token3', $tokens[2]);
    $I->wait(1);
    $I->click('Run');
    $I->wait(1);

    for ($o = 0; $o < $team_size; $o++) {
        $I->see('Observed: Mark ' . ($o + 1));
        $I->click('Begin');
        $I->wait(1);

        for ($d = 0; $d < 8; $d++) {
            for ($q = 0; $q < 8; $q++) {
                $answer = 'answer' . (($d * 8) + $q);
                $random = rand(0, 4);
                $I->selectOption("form input[name=$answer]", $random);
            }

            if ($d < 7)
                $I->click('Save and next dimension...');
            else
                $I->click('Save');
            $I->wait(1);
        }
    }

    $I->see('success');

    $I->click('Home');
}
