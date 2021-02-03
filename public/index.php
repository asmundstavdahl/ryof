<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../functions.php';

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Laminas\Diactoros\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$config = require __DIR__ . '/../config/prod.php';
$container = new MyContainer($config);
$app = new AppKernel($container);

try {
    $response = $app->handle($request);
} catch (Error $e) {
    http_response_code(500);
    exit("500 Internal Server Error");
} catch (Exception $e) {
    http_response_code(400);
    exit("400 Bad Request");
}

sendResponse($response);
