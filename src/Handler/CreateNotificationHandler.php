<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\CreateNotification;
use App\Entity\Notification;
use App\Factory\NotificationFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateNotificationHandler
{
    public function __construct(
        private NotificationFactory $notificationFactory,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateNotification $command): Notification
    {
        $notification = $this->notificationFactory->createNewNotification(
            $command->recipientEmail,
            $command->subject,
            $command->body,
        );

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $notification;
    }
}
