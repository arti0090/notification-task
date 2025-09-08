<?php

namespace App\Sender;

use App\Entity\Notification;

interface NotificationSenderInterface
{
    public function send(Notification $notification): void;
}
