<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

/**
 * Keep tabs on which paths can be accessed by which user roles.
 */
class AccessWhitelist extends ArrayContainer
{
    /**
     * @return array roles that are allowed to access the path
     */
    public function getAllowedRolesForPath(string $path): array
    {
        return $this->get($path);
    }
}
