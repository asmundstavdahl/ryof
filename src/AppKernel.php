<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface;

class AppKernel implements RequestHandlerInterface
{
    protected $container;
    protected $flow;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->flow = $container->get("flow");
    }

    public function handle(Request $request): Response
    {
        $stageClass = array_shift($this->flow);

        $stage = new $stageClass($this->container);

        $response = $stage->process($request, $this);

        return $response;
    }
}
