<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\SendNotification;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsController]
class SendNotificationController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(Notification $notification): Response
    {
        $this->messageBus->dispatch(
            new SendNotification((string) $notification->getId())
        );

        return new JsonResponse(
            ['message' => 'Notification accepted and will be sent soon.'],
            Response::HTTP_ACCEPTED,
        );
    }
}
