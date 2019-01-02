<?php

$random = rand(111, 999);
$email = "coach@example.com";
$password = "password$random";

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that reset password works');

$I->amOnPage(Yii::$app->homeUrl);

$I->click('Reiniciar contraseña');

$I->waitForText('Solicitud de reinicio de contraseña', 30, 'h1');

$I->fillField('PasswordResetRequestForm[email]', $email);
$I->click('Solicitar');

$I->waitForText('Revise su buzón de correo para más instrucciones');

$message = $I->grabLastSentEmail();
//$I->seeInLastEmailSubject('Reinicio de contraseña para VACH');

var_dump($message);

$I->amOnUrl($link);

$I->waitForText('Reiniciar contraseña', 30, h1);

$I->fillField('ResetPasswordForm[password]', $password);
$I->click('Guardar');

$I->waitForText('Nueva contraseña guardada');

$I->login('coach', $password);
