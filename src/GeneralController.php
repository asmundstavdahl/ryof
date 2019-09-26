<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\HtmlResponse;

class GeneralController
{
    public function home(array $config, Request $request) : Response
    {
        return new HtmlResponse("Welcome home.");
    }

    public function info(array $config, Request $request) : Response
    {
        ob_start();
        phpinfo();
        return new HtmlResponse(ob_get_clean());
    }

    public function square(array $config, Request $request, int $x) : Response
    {
        return new HtmlResponse(sprintf('%d^2 = %d', $x, $x*$x));
    }

    public function sentence(array $config, Request $request, array $items) : Response
    {
        return new HtmlResponse(
            implode(" ",
                array_merge(
                    [array_shift($items)],
                    array_map('strtolower', $items)))
            . '.');
    }
}
