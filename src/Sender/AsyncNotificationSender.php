<?php

declare(strict_types=1);

namespace App\Sender;

use App\Entity\Notification;
use App\Message\SendNotificationMessage;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class AsyncNotificationSender extends AbstractNotificationSender
{
    public function __construct(
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct($notificationRepository, $entityManager);
    }

    protected function sendNotification(Notification $notification): void
    {
        $this->messageBus->dispatch(new SendNotificationMessage($notification->getId()));
    }
}
