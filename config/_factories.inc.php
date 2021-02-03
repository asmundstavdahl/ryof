<?php

use Psr\Container\ContainerInterface;

function simpleFactorize(array $classes): array
{
    $simpleFactories = [];

    foreach ($classes as $class) {
        $simpleFactories[$class] =
            function (ContainerInterface $container)
            use ($class) {
                return new $class($container);
            };
    }

    return $simpleFactories;
}
