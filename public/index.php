<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../functions.php';

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$config = require __DIR__ . '/../config.php';

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$app = new AppKernel($config);

try {
    $response = $app->handle($request);
} catch(RequestNotHandledException $e) {
    http_response_code(404);
    exit("404 Not Found\n\nNo response generated.");
}

sendResponse($response);
