<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SendNotificationMessage;
use App\Repository\NotificationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Webmozart\Assert\Assert;

#[AsMessageHandler]
readonly class SendNotificationMessageHandler
{
    public function __construct(
        private NotificationRepository $notificationRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(SendNotificationMessage $sendNotificationMessage): void
    {
        $notification = $this->notificationRepository->findOneBy(['id' => $sendNotificationMessage->notificationId]);
        Assert::notNull($notification);

        // logging to logger as a "fake" sending
        $this->logger->info(
            sprintf("ASYNC NOTIFICATION: To: %s, Subject: %s, Body: %s\n",
                $notification->getRecipientEmail(),
                $notification->getSubject(),
                $notification->getBody()
            )
        );
    }
}
