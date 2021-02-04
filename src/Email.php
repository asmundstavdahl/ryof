<?php

declare(strict_types=1);

require_once __DIR__ . "/../functions.php";

use Psr\Container\ContainerInterface;

class Email
{
    /**
     * @var string
     */
    public $to;
    /**
     * @var string
     */
    public $subject;
    /**
     * @var string
     */
    public $message;
    /**
     * @var array
     */
    public  $headers;

    private static $archiveCount = 0;

    public function __construct(
        string $to,
        string $subject,
        string $message,
        array  $headers = []
    ) {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = $headers;
    }

    public function __toString(): string
    {
        $parts = [];
        array_push($parts, "To: {$this->to}");
        array_push($parts, "Subject: {$this->subject}");
        foreach ($this->headers as $key => $value) {
            array_push($parts, "{$key}: {$value}");
        }
        array_push($parts, "");
        array_push($parts, $this->message);
        array_push($parts, "");
        return implode("\r\n", $parts);
    }

    /**
     * Arkivér e-posten til fil
     */
    public function archive()
    {
        file_put_contents($this->getEmailArchiveFilename(), (string)$this);
    }

    protected function getEmailArchiveFilename()
    {
        $requestId = getRequestIdentifier();
        $archiveDir = __DIR__ . "/../var/email/";
        $filename = $requestId . "-" . (++self::$archiveCount) . ".eml";

        if (!is_dir($archiveDir)) {
            mkdir($archiveDir);
        }

        return "{$archiveDir}{$filename}";
    }
}
