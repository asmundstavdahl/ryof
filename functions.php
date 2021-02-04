<?php

declare(strict_types=1);

use Controller\AttachmentController;
use Entity\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Send an HTTP response
 *
 * @return void
 */
function sendResponse(ResponseInterface $response)
{
    $http_line = sprintf(
        'HTTP/%s %s %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase()
    );

    header($http_line, true, $response->getStatusCode());

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header("$name: $value", false);
        }
    }

    $stream = $response->getBody();

    if ($stream->isSeekable()) {
        $stream->rewind();
    }

    while (!$stream->eof()) {
        echo $stream->read(1024 * 8);
    }
}

/**
 * Render a PHP template with given context's variables.
 */
function render(string $templateName, array $context = []): string
{
    $templatePath = __DIR__ . "/templates/{$templateName}.php";

    if (!file_exists($templatePath)) {
        $msg = "No such template ('{$templateName}')";
        echo $msg;
        throw new Exception($msg);
    }

    extract($context);
    ob_start();
    require $templatePath;
    return ob_get_clean();
}

/**
 * Escape a string to make it HTML safe.
 */
function e(string $string): string
{
    return htmlspecialchars($string);
}

function tr(string $text): string
{
    $translations = require __DIR__ . "/translations.php";

    if (array_key_exists($text, $translations)) {
        return $translations[$text];
    }
    return $text;
}

/**
 * Make a host-relative link.
 *
 * @param ContainerInterface $container e.g. new ArrayContainer(["appBasePath" => "/myapp"])
 * @param string $path e.g. "/form.show"
 * @param array $params e.g ["id" => 42]
 * @return string e.g. "/myapp/form.show?id=42"
 */
function linkToPath(ContainerInterface $container, string $path, array $params = []): string
{
    $appBasePath = $container->get("appBasePath") ?: "/";
    $path = $appBasePath . $path;

    if (empty($params)) {
        return $path;
    }

    $queryString = "";
    foreach ($params as $key => $value) {
        $queryString .= "{$key}={$value}&";
    }
    $queryString = trim($queryString, "&");

    return "{$path}?{$queryString}";
}

/**
 * Out: request->path with appBasePath removed from the beginning
 *
 * appBasePath must be "" or "/…"
 */
function getAppPathFromRequest(ContainerInterface $container, RequestInterface $request): string
{
    $requestPath = $request->getUri()->getPath();
    $appBasePath = $container->get("appBasePath");

    $path = substr($requestPath, strlen($appBasePath));

    return $path;
}

/**
 * Uniquely identify the current HTTP request.
 */
function getRequestIdentifier(): string
{
    static $id = null;
    if ($id === null) {
        $ut = microtime();
        $id = date("Y-m-d") . "-{$ut}-" . substr(md5(print_r($_SERVER, true)), 0, 5);
    }
    return $id;
}
