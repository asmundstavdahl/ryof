<?php

declare(strict_types=1);

namespace Flow;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TestAuthenticationStage implements MiddlewareInterface
{
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $uri = $request->getRequestTarget();

        if ($uri === "/authenticate") {
            $this->container->get("authentication service")($request);
        } else {
            return new HtmlResponse(render("unauthed-please-login"), 401);
        }

        return $handler->handle($request);
    }
}
