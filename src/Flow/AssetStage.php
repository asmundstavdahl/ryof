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

class AssetStage implements MiddlewareInterface
{
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $URIPath = $request->getUri()->getPath();
        $URIPath = preg_replace("/\.\.+/", ".", $URIPath);

        $assetPath = __DIR__ . "/../../public/{$URIPath}";

        if (!file_exists($assetPath) || is_dir($assetPath)) {
            return $handler->handle($request);
        }

        $assetParts = explode(".", $assetPath);
        $assetExt = array_pop($assetParts);
        switch ($assetExt) {
            case "css":
                $assetMime = "text/css";
                break;

            case "js":
                $assetMime = "application/javascript";
                break;

            case "html":
                $assetMime = "text/html";
                break;

            default:
                $assetMime = "text/plain";
        }

        $body = file_get_contents($assetPath);
        $headers = ["Content-Type" => "{$assetMime}; charset=utf-8"];

        return new HtmlResponse($body, 200, $headers);
    }
}
