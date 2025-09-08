<?php

declare(strict_types=1);

namespace App\Sender;

use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractNotificationSender implements NotificationSenderInterface
{
    public function __construct(
        protected readonly NotificationRepository $notificationRepository,
        protected readonly EntityManagerInterface $entityManager
    ) {
    }

    public function send(Notification $notification): void
    {
        try {
            $this->sendNotification($notification);

            $notification->setStatus(NotificationStatus::Sent);
            $notification->setSentAt(new \DateTimeImmutable());

            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $notification->setStatus(NotificationStatus::Failed);

            $this->entityManager->flush();

            throw $exception;
        }
    }

    abstract protected function sendNotification(Notification $notification): void;
}
