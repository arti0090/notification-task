<?php

declare(strict_types=1);

namespace App\Tests\Messenger;

use App\Command\CreateNotification;
use App\Entity\Notification;
use App\Handler\CreateNotificationHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class NotificationMessengerTest extends KernelTestCase
{
    private MessageBusInterface $messageBus;
    private CreateNotificationHandler $handler;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->messageBus = $container->get(MessageBusInterface::class);
        $this->handler = $container->get(CreateNotificationHandler::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    #[Test]
    public function it_dispatches_create_notification_command(): void
    {
        $command = new CreateNotification(
            'test@example.com',
            'Test Subject',
            'Test Body'
        );

        $this->messageBus->dispatch($command);

        $repository = $this->entityManager->getRepository(Notification::class);
        $notification = $repository->findOneBy(['recipientEmail' => 'test@example.com']);

        //additional check if a handler was declared/exists
        $this->assertNotNull($this->handler, 'Handler should exist');

        $this->assertNotNull($notification);
        $this->assertEquals('test@example.com', $notification->getRecipientEmail());
        $this->assertEquals('Test Subject', $notification->getSubject());
        $this->assertEquals('Test Body', $notification->getBody());
        $this->assertNotNull($notification->getCreatedAt());
    }
}
