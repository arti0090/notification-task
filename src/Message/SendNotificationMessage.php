<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
class SendNotificationMessage
{
    public function __construct(
        public int $notificationId
    ) {
    }
}
