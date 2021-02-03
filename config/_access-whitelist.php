<?php

declare(strict_types=1);

return new AccessWhitelist([
    "/login" => ["any"],
    "/logout" => ["any"],

    "/" => ["any"],
    "/home" => ["any"],

    "/roles.overview" => ["admin"],
    "/roles.new" => ["any"],
    "/roles.give" => ["any"],
    "/roles.revoke" => ["admin"],

    "/dev-menu.set-session" => ["any"],
]);
