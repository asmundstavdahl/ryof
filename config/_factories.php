<?php

declare(strict_types=1);

require_once __DIR__ . "/_factories.inc.php";

use Psr\Container\ContainerInterface;

$simpleFactoryClasses = [
    Authenticator::class,
    Repository\AvailableRoleRepository::class,
    Repository\FileRepository::class,
    Repository\RoleRepository::class,
    Repository\SettingRepository::class,
    Repository\UserRepository::class,
];

return array_merge(simpleFactorize($simpleFactoryClasses));
