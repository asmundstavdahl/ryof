<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ArrayContainer implements ContainerInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get($id)
    {
        if (isset($this->data[$id])) {
            return $this->data[$id];
        }

        throw new class ("data key not found: {$id}") extends Exception implements NotFoundExceptionInterface
        {
        };
    }

    public function has($id): bool
    {
        return isset($this->data[$id]);
    }

    public function set($id, $value)
    {
        return $this->data[$id] = $value;
    }
}
