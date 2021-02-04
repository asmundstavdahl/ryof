<?php

declare(strict_types=1);

interface Mailer
{
    public function send(Email $email): bool;
}
