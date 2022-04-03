<?php

declare(strict_types=1);

namespace Repository;

class AvailableRoleRepository
{
    /**
     * @return string[]
     */
    public function getForUsername(string $username): array
    {
        return [
            'admin',
        ];
    }
}
