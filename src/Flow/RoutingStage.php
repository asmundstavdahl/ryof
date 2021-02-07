<?php

declare(strict_types=1);

namespace Flow;

use ErrorReporter;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RoutingStage implements MiddlewareInterface
{
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $uri = getAppPathFromRequest($this->container, $request);
        $routes = $this->container->get("routes");

        $maybeAssetPath = $this->container->get("publicDir") . $uri;
        if (file_exists($maybeAssetPath) && !is_dir($maybeAssetPath)) {
            return new HtmlResponse(file_get_contents($maybeAssetPath));
        }

        try {
            list($callback, $matches) = self::findMatchingRoute($uri, $routes);
        } catch (\NoMatchingRouteException $e) {
            return $handler->handle($request);
        }

        if (self::arrayHasNonNumericKey($matches)) {
            $args = $this->adaptControllerArgs($callback, $matches, $this->container, $request);
        } else {
            $args = array_merge([$this->container, $request], $matches);
        }

        try {
            return call_user_func_array($callback, $args);
        } catch (\PDOException $ex) {
            /**
             * @var ErrorReporter
             */
            $errorReporter = $this->container->get(ErrorReporter::class);
            $errorReporter->report($ex);

            $message = $ex->getMessage();
            return new HtmlResponse(
                render(
                    "500",
                    [
                        "reason" => "Databasefeil. Feilen har blitt rapportert til Åsmund Stavdahl men gjerne ta kontakt med han og beskriv hva du nettopp gjorde.
                        <br>
                        <pre>{$message}</pre>"
                    ]
                )
            );
        } catch (\Throwable $ex) {
            /**
             * @var ErrorReporter
             */
            $errorReporter = $this->container->get(ErrorReporter::class);
            $errorReporter->report($ex);

            $message = $ex->getMessage();
            return new HtmlResponse(
                render(
                    "500",
                    [
                        "reason" => "Beklager – det oppstod visst en feil. Feilen har blitt rapportert til Åsmund Stavdahl men gjerne ta kontakt med han og beskriv hva du nettopp gjorde.
                        <br>
                        <pre>{$message}</pre>"
                    ]
                )
            );
        }
    }

    private static function arrayHasNonNumericKey(array $arr): bool
    {
        foreach (array_keys($arr) as $key) {
            if (!is_int($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array [callable $callback, array $matches]
     */
    private static function findMatchingRoute(string $uri, array $routes): array
    {
        foreach ($routes as $pattern => $callback) {
            $matches = [];

            $escapedPattern = str_replace('/', '\/', $pattern);
            $patternRe = "/^{$escapedPattern}$/";

            if (preg_match($patternRe, $uri, $matches)) {
                return [$callback, $matches];
            }
        }

        throw new \NoMatchingRouteException();
    }

    /**
     * @return array Argument list to be used when invoking the callback
     */
    private function adaptControllerArgs($callback, $matches, $container, $request): array
    {
        $rm = new \ReflectionMethod(...$callback);
        $parameters = $rm->getParameters();

        $defaultParameters = [$container, $request];

        foreach ($parameters as $parameter) {
            if (array_key_exists($parameter->name, $matches)) {
                $matchedString = $matches[$parameter->name];

                switch ($type = $parameter->getType()) {
                    case int::class:
                        $safeMatchedValue = intval($matchedString);
                        break;

                    case float::class:
                        $safeMatchedValue = floatval($matchedString);
                        break;

                    case 'array':
                        $safeMatchedValue = explode(',', $matchedString);
                        break;

                    case string::class:
                        $safeMatchedValue = $matchedString;
                        break;

                    default:
                        $fmt = 'Unsupported type %s in %s::%s';
                        $msg = sprintf($fmt, $type, ...$callback);
                        throw new \TypeError($msg);
                        break;
                }

                $args[] = $safeMatchedValue;
            } else {
                $args[] = array_shift($defaultParameters);
            }
        }

        return $args;
    }
}
