<?php

declare(strict_types=1);

namespace Flow;

use AccessWhitelist;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AccessStage implements MiddlewareInterface
{
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        /**
         * @var AccessWhitelist
         */
        $whitelist = $this->container->get(\AccessWhitelist::class);
        $path = getAppPathFromRequest($this->container, $request);

        try {
            $allowedRoles = $whitelist->getAllowedRolesForPath($path);
        } catch (NotFoundExceptionInterface $ex) {
            error_log("Ingen har tilgang til {$path}");
            return new HtmlResponse(render("403-access-denied", ["reason" => "Ingen har tilgang hit."]), 403);
        }

        if (array_search("any", $allowedRoles) !== false) {
            return $handler->handle($request);
        }

        $session = $this->container->get(\Session::class);
        $rolesOfUser = $session->getRoles($this->container);

        $allowedRolesOfUser = array_intersect($allowedRoles, $rolesOfUser);

        if (count($allowedRolesOfUser) >= 1) {
            return $handler->handle($request);
        }

        return new HtmlResponse(render("403-access-denied", ["reason" => "Du har ikke tilgang hit."]), 403);
    }
}
