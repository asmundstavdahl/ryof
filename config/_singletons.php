<?php

declare(strict_types=1);

return [
    Authenticator::class,
    ErrorReporter::class,
    RoutineMailer::class,
    Repository\AvailableRoleRepository::class,
    Repository\FileRepository::class,
    Repository\RoleRepository::class,
    Repository\SettingRepository::class,
    Repository\UserRepository::class,
];
