<?php

return [
    'allow_register' => false,
    'adminEmail' => 'admin@example.com',
    'minimal_consciousness' => 4 / 3,
    'good_consciousness' => 4 * 2 / 3,
    'server_status' => 'online',
    'send_wheel_answers' => true,
    'user.passwordResetTokenExpire' => 3600,
    //
    'default_product_id' => 1,
    'default_quantity' => 10,
    //
    'payu_test' => true,
    'payu_merchant_id' => 508029,
    'payu_account_id' => 512322,
    'payu_api_key' => '4Vj8eK4rloUd272L48hsrarnUA',
    'payu_tax' => '0',
    'payu_tax_return_base' => '0',
    'payu_currency' => 'USD',
    'payu_action_url' => 'https://sandbox.gateway.payulatam.com/ppp-web-gateway',
    'payu_response_url' => 'http://**********:8080/vach/web/index.php?r=account%2Fresponse',
    'payu_confirmation_url' => 'http://**********:8080/vach/web/index.php?r=account%2Fconfirmation',
];
