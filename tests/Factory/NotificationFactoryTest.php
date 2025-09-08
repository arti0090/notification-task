<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\NotificationInterface;
use App\Enum\NotificationStatus;
use App\Factory\NotificationFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NotificationFactoryTest extends TestCase
{
    private NotificationFactory|MockObject $factory;

    protected function setUp(): void
    {
        $this->factory = new NotificationFactory();
    }

    #[Test]
    public function test_it_creates_a_notification(): void
    {
        $notification = $this->factory->createNewNotification(
            'test123@mail.com',
            'test subject',
            'test body'
        );

        $this->assertInstanceOf(NotificationInterface::class, $notification);
        $this->assertEquals('test123@mail.com', $notification->getRecipientEmail());
        $this->assertEquals('test subject', $notification->getSubject());
        $this->assertEquals('test body', $notification->getBody());

        $this->assertNotNull($notification->getCreatedAt());
        $this->assertEquals($notification->getStatus(), NotificationStatus::Pending);
    }
}
