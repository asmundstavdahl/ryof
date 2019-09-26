<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AppKernel
{
    protected $config;

    function __construct(array $config)
    {
        $this->config = $config;
    }

    public function handle(Request $request) : Response
    {
        foreach ($this->config['flow'] as $stageClass) {
            $stage = new $stageClass($this->config);
            $maybeResponse = $stage->process($request);

            if (is_a($maybeResponse, Response::class)) {
                $response = $maybeResponse;
                return $maybeResponse;
            }
        }

        throw new RequestNotHandledException();
    }
}
