<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RoutingStage implements FlowStage
{
    protected $config;

    function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return null|Response Returns null if no route pattern matched the query
     */
    public function process(Request $request)
    {
        $uri = $request->getRequestTarget();
        $routes = $this->config['routes'];

        try {
            list($callback, $matches) = self::findMatchingRoute($uri, $routes);
        } catch (NoMatchingRouteException $e) {
            return null;
        }

        if (self::arrayHasNonNumericKey($matches)) {
            $args = $this->adaptControllerArgs($callback, $matches, $this->config, $request);
        } else {
            $args = $matches;
        }

        return call_user_func_array($callback, $args);
    }

    private static function arrayHasNonNumericKey(array $arr) : bool
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
    private static function findMatchingRoute(string $uri, array $routes) : array
    {
        foreach ($routes as $pattern => $callback) {
            $matches = [];

            $escapedPattern = str_replace('/', '\/', $pattern);
            $patternRe = "/^{$escapedPattern}$/";

            if (preg_match($patternRe, $uri, $matches)) {
                return [$callback, $matches];
            }
        }

        throw new NoMatchingRouteException();
    }

    /**
     * @return array Argument list to be used when invoking the callback
     */
    private function adaptControllerArgs($callback, $matches, $config, $request) : array
    {
        $rm = new ReflectionMethod(...$callback);
        $parameters = $rm->getParameters();

        $defaultParameters = [$config, $request];

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

                    default:
                        $fmt = 'Unsupported type %s in %s::%s';
                        $msg = sprintf($fmt, $type, ...$callback);
                        throw new TypeError($msg);
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
