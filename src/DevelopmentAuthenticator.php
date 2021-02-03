<?php

use Psr\Container\ContainerInterface;

class DevelopmentAuthenticator implements Authenticator
{
    public function authenticate(ContainerInterface $container)
    {
        $_SESSION["name"] = $container->get("test name");
        $_SESSION["username"] = $container->get("test username");
        $_SESSION["email"] = $container->get("test email");
    }
}
