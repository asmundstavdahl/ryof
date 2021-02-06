<?php

$baseConfig = require __DIR__ . "/prod.php";

$devConfigPatch = [
    "example name" => "Åsmund Test Stavdahl",
    "example username" => "asmund",
    "example email" => "asmundstavdahl@github.com",
    Mailer::class => new MockMailer(),
    Authenticator::class => new ExampleAuthenticator(),
];

return array_merge($baseConfig, $devConfigPatch);
