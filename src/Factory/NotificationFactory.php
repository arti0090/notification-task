<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Notification;

class NotificationFactory
{
    private function createNew(): Notification
    {
        $notification = new Notification();
        $notification->setCreatedAt(new \DateTimeImmutable());

        return $notification;
    }

    public function createNewNotification(string $recipientEmail, string $subject, string $body): Notification
    {
        $notification = $this->createNew();

        $notification->setRecipientEmail($recipientEmail);
        $notification->setSubject($subject);
        $notification->setBody($body);

        return $notification;
    }
}
