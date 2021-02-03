<?php

declare(strict_types=1);

$dsn = "sqlite:" . __DIR__ . "/../var/app.sqlite3";
$pdo = new PDO($dsn);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $pdo;
