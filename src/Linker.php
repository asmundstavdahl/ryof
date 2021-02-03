<?php

declare(strict_types=1);

class Linker
{
    private $basePath = "";

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function to(string $path, array $params = []): string
    {
        $path = $this->basePath . $path;

        if (empty($params)) {
            return $path;
        }

        $queryString = "";
        foreach ($params as $key => $value) {
            $queryString .= "{$key}={$value}&";
        }
        $queryString = trim($queryString, "&");

        return "{$path}?{$queryString}";
    }
}
