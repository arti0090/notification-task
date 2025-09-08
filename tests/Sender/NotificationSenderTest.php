<?php

declare(strict_types=1);

namespace App\Tests\Sender;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Sender\NotificationSender;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final class NotificationSenderTest extends TestCase
{
    private MockObject|NotificationRepository $notificationRepository;
    private MockObject|EntityManagerInterface $entityManager;
    private MockObject|WorkflowInterface $workflow;
    private MockObject|LoggerInterface $logger;
    private NotificationSender $sender;

    protected function setUp(): void
    {
        $this->notificationRepository = $this->createMock(NotificationRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->workflow = $this->createMock(WorkflowInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->sender = new NotificationSender(
            $this->notificationRepository,
            $this->entityManager,
            $this->workflow,
            $this->logger
        );
    }

    // tests could be extended with other files, and more scenarios (f.e. incorrect data for message)
    #[Test]
    public function it_logs_a_notification_message(): void
    {
        $notification = new Notification();
        $notification->setRecipientEmail('test@example.com');
        $notification->setSubject('Test subject');
        $notification->setBody('Test body');

        $this->logger->expects($this->once())
            ->method('info')
            ->with($this->stringContains('SYNC NOTIFICATION: To: test@example.com, Subject: Test subject, Body: Test body'))
        ;

        $this->sender->send($notification);
    }
}
