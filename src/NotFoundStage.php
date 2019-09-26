<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundStage implements FlowStage
{
    public function process(Request $request) : Response
    {
        return new HtmlResponse("404 Not Found");
    }
}
