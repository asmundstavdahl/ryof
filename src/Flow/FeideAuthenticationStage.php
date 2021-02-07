<?php

declare(strict_types=1);

namespace Flow;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \Session;

class FeideAuthenticationStage implements MiddlewareInterface
{
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        /**
         * @var Session
         */
        $session = $this->container->get(Session::class);
        $authed = $session->has("username") && $session->has("name") && $session->has("email");
        $path = getAppPathFromRequest($this->container, $request);

        if ($path === "/login" || $path === "/logout") {
            return $handler->handle($request);
        }

        if ($authed) {
            return $handler->handle($request);
        }

        $body = render("unauthed-please-login", ["returnTo" => $path]);
        return new HtmlResponse($body, 401);
    }
}
