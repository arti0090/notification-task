<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\SendNotification;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

class SendNotificationController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(Notification $notification): void
    {
        $this->messageBus->dispatch(
            new SendNotification((string) $notification->getId())
        );
    }
}
