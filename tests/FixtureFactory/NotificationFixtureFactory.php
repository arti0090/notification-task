<?php

namespace App\Tests\FixtureFactory;

use App\Entity\Notification;
use App\Enum\NotificationStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Notification>
 */
final class NotificationFixtureFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Notification::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'body' => self::faker()->text(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'recipientEmail' => self::faker()->email(),
            'status' => self::faker()->randomElement(NotificationStatus::cases()),
            'subject' => self::faker()->text(255),
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(
            function(Notification $notification): void {}
        );
    }
}
