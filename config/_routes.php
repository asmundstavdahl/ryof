<?php

declare(strict_types=1);

use Controller\AdminController;
use Controller\MasterFormController;
use Laminas\Diactoros\Response\RedirectResponse;

return [
    '/login' => [Controller\AuthenticationController::class, 'login'],
    '/logout' => [Controller\AuthenticationController::class, 'logout'],

    '/home' => [Controller\HomeController::class, "home"],

    '/roles.overview' => [Controller\RolesController::class, "overview"],
    '/roles.new' => [Controller\RolesController::class, "newAction"],
    '/roles.give' => [Controller\RolesController::class, "give"],
    '/roles.revoke' => [Controller\RolesController::class, "revoke"],

    '/dev-menu.set-session' => [Controller\DevelopmentMenuController::class, "submitSessionData"],

    '/index.php' => (function () {
        return new RedirectResponse("home");
    }),
    '/' => (function () {
        return new RedirectResponse("home");
    }),
];
