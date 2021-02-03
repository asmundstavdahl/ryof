<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

class AccessWhitelist extends ArrayContainer
{
    public function getAllowedRolesForPath(string $path)
    {
        return $this->get($path);
    }
}
