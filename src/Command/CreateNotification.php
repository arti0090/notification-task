<?php

declare(strict_types=1);

namespace App\Command;

readonly class CreateNotification
{
    public function __construct(
        public string $recipientEmail,
        public string $subject,
        public string $body
    ) {
    }
}
