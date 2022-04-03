<?php

declare(strict_types=1);

namespace Repository;

class RoleRepository
{
    public function getAll(): array
    {
        return [
            'admin',
            'guest',
        ];
    }
}
