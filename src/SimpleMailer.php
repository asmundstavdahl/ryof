<?php

declare(strict_types=1);

class SimpleMailer implements Mailer
{
    public function send(Email $email): bool
    {
        $email->headers = array_merge([
            "Charset" => "utf8",
            "From" => "RYOF App <ryof-app@localhost>",
            "Content-Type" => "text/html; charset=UTF-8",
        ], $email->headers);

        $email->archive();

        $email->message = "<html><body><pre>" . htmlentities($email->message) . "</pre></body></html>";

        return $this->actuallySend($email);
    }

    protected function actuallySend(Email $email): bool
    {
        return mail(
            $email->to,
            $email->subject,
            $email->message,
            $email->headers
        );
    }
}
