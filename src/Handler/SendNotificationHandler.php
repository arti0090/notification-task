<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\SendNotification;
use App\Repository\NotificationRepository;
use App\Sender\NotificationSenderInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Webmozart\Assert\Assert;

#[AsMessageHandler]
final readonly class SendNotificationHandler
{
    public function __construct(
        private NotificationRepository $notificationRepository,
        private NotificationSenderInterface $notificationSender,
    ) {
    }

    public function __invoke(SendNotification $command): void
    {
        $notification = $this->notificationRepository->findOneBy(['id' => $command->id]);
        Assert::notNull($notification);

        $this->notificationSender->send($notification);
    }
}
