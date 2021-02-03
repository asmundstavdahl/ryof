<?php

$baseConfig = require __DIR__ . "/prod.php";

$testConfigPatch = [
    "test name" => "name",
    "test username" => "username",
    "test roles" => "roles",
    Authenticator::class => new DevelopmentAuthenticator(),
];

return array_merge_recursive($baseConfig, $testConfigPatch);
