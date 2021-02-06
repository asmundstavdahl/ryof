<?php

$baseConfig = require __DIR__ . "/prod.php";

$testConfigPatch = [
    "example name" => "name",
    "example username" => "username",
    "example roles" => "roles",
    Authenticator::class => new ExampleAuthenticator(),
];

return array_merge_recursive($baseConfig, $testConfigPatch);
