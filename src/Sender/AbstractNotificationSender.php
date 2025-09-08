<?php

declare(strict_types=1);

namespace App\Sender;

use App\Entity\Notification;
use App\Enum\NotificationTransition;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

abstract class AbstractNotificationSender implements NotificationSenderInterface
{
    public function __construct(
        protected readonly NotificationRepository $notificationRepository,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly WorkflowInterface $workflow,
    ) {
    }

    public function send(Notification $notification): void
    {
        try {
            $this->workflow->apply($notification, NotificationTransition::SEND->value);

            $this->sendNotification($notification);

            $notification->setSentAt(new \DateTimeImmutable());

            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->workflow->apply($notification, NotificationTransition::FAIL->value);

            $this->entityManager->flush();

            throw $exception;
        }
    }

    abstract protected function sendNotification(Notification $notification): void;
}
