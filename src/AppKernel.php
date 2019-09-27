<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface;

class AppKernel implements RequestHandlerInterface
{
    protected $config;
    protected $flow;

    function __construct(array $config)
    {
        $this->config = $config;
        $this->flow = $config['flow'];
    }

    public function handle(Request $request) : Response
    {
        $stageClass = array_shift($this->flow);

        $stage = new $stageClass($this->config);

        $response = $stage->process($request, $this);

        return $response;
    }
}
