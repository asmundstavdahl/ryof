<?php

declare(strict_types=1);

if (defined("STDIN")) {
    $_SERVER["HTTP_HOST"] = "terminal";
}

$is_HTTPS =
    (!empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['SERVER_PORT'])
        && $_SERVER['SERVER_PORT'] == 443);

$httpProtocol = $is_HTTPS ? "https" : "http";

$prodConfig = [
    'flow'   => require __DIR__ . '/_flow.php',
    'routes' => require __DIR__ . '/_routes.php',
    'services' => require __DIR__ . '/_services.php',
    Session::class => require __DIR__ . '/_session.php',
    AccessWhitelist::class => require __DIR__ . '/_access-whitelist.php',
    PDO::class => require __DIR__ . '/_database.php',
    'factories' => require __DIR__ . '/_factories.php',
    'singletons' => require __DIR__ . '/_singletons.php',

    'publicDir' => __DIR__ . '/../public',
    'appBasePath' => '',
    Mailer::class => new SimpleMailer(),
    Authenticator::class => new ExampleAuthenticator(),

    'email address for error reports' => "asmund@localhost",

    'HTTP protocol' => $httpProtocol,
];

$localConfig = [];
if (file_exists(__DIR__ . "/local.php")) {
    $localConfig = require __DIR__ . "/local.php";
}

$config = array_merge($prodConfig, $localConfig);

$config[Linker::class] = new Linker("{$httpProtocol}://{$_SERVER["HTTP_HOST"]}{$config["appBasePath"]}/");

return $config;
