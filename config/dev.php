<?php

$baseConfig = require __DIR__ . "/prod.php";

$testConfigPatch = [
    "test name" => "Åsmund Test Stavdahl",
    "test username" => "asmund",
    "test email" => "asmundstavdahl@github.com",
    Mailer::class => new MockMailer(),
    Authenticator::class => new DevelopmentAuthenticator(),
];

return array_merge($baseConfig, $testConfigPatch);
