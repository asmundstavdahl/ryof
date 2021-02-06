<?php

use Psr\Container\ContainerInterface;

interface Authenticator
{
    public function authenticate(ContainerInterface $container);
}
