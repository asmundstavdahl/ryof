<?php

declare(strict_types=1);

/**
 * Send emails to browser rather than actually sending them to a mail server.
 */
class MockMailer implements Mailer
{
    private static $browser = "firefox";

    public function send(Email $email): bool
    {
        $emailFile = tempnam("/tmp", "eml") . ".html";

        file_put_contents($emailFile, "<pre>
To:
    " . preg_replace("[,;]", "\n    ", $email->to) . "

Subject:
    {$email->subject}

" . (!empty($email->headers) ? "
Headers:
    " . print_r($email->headers, true) . "" : "") . "

Message:
    " . str_replace("\n", "\n    ", $email->message));

        $browser = self::$browser;

        `DISPLAY=:0 {$browser} {$emailFile}`;

        return true;
    }
}
