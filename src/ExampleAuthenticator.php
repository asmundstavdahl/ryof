<?php

use Psr\Container\ContainerInterface;

class ExampleAuthenticator implements Authenticator
{
    public function authenticate(ContainerInterface $container)
    {
        $_SESSION["name"] = $container->get("example name");
        $_SESSION["username"] = $container->get("example username");
        $_SESSION["email"] = $container->get("example email");
    }
}
