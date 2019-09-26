<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

interface FlowStage
{
    /**
     * @return null|Response Returns null or a final response
     */
    public function process(Request $request);
}
