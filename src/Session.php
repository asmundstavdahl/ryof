<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Repository\RoleRepository;

class Session extends ArrayContainer
{
    public function getUsername()
    {
        return $this->get("username");
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function getEmail()
    {
        return $this->get("email");
    }

    public function getRoles(ContainerInterface $container)
    {
        /**
         * @var RoleRepository
         */
        $roleRepo = $container->get(RoleRepository::class);
        return $roleRepo->getForUsername($this->getUsername($container));
    }

    public function __set(string $id, $value)
    {
        $this->set($id, $value);
        return $_SESSION[$id] = $value;
    }
}
