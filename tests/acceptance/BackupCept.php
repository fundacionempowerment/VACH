<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that backup works');

$I->loginAsAdmin();

$I->clickMainMenu('Admin', 'Backup');
$I->wait(1);

$I->waitForText('Backup sent!');

$I->seeInLastEmailSubject("Backup");
