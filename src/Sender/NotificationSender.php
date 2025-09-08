<?php

declare(strict_types=1);

namespace App\Sender;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class NotificationSender extends AbstractNotificationSender
{
    public function __construct(
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
        WorkflowInterface $workflow,
        protected readonly LoggerInterface $logger,
    ) {
        parent::__construct($notificationRepository, $entityManager, $workflow);
    }

    protected function sendNotification(Notification $notification): void
    {
        // logging to logger as a "fake" sending
        $this->logger->info(
            sprintf("SYNC NOTIFICATION: To: %s, Subject: %s, Body: %s\n",
                $notification->getRecipientEmail(),
                $notification->getSubject(),
                $notification->getBody()
            )
        );
    }
}
