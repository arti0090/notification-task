<?php

declare(strict_types=1);

namespace App\Sender;

use App\Entity\Notification;
use App\Message\SendNotificationMessage;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class AsyncNotificationSender extends AbstractNotificationSender
{
    public function __construct(
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
        WorkflowInterface $workflow,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct($notificationRepository, $entityManager, $workflow);
    }

    protected function sendNotification(Notification $notification): void
    {
        $this->messageBus->dispatch(new SendNotificationMessage($notification->getId()));
    }
}
