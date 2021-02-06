<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Laminas\Diactoros\Response\HtmlResponse;

class NotFoundStage implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        return new HtmlResponse("404 Not Found");
    }
}
