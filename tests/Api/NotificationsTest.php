<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

use App\Entity\Notification;
use App\Tests\Factory\NotificationFactory;
use PHPUnit\Framework\Attributes\Test;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class NotificationsTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    #[Test]
    public function it_gets_notifications(): void
    {
        NotificationFactory::createMany(100);

        self::createClient()->request('GET', '/api/notifications');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertMatchesResourceCollectionJsonSchema(Notification::class);

        self::assertJsonContains([
            '@context' => '/api/contexts/Notification',
            '@id' => '/api/notifications',
            '@type' => 'Collection',
            'totalItems' => 100,
            'view' => [
                '@id' => '/api/notifications?page=1',
                '@type' => 'PartialCollectionView',
                'first' => '/api/notifications?page=1',
                'last' => '/api/notifications?page=4',
                'next' => '/api/notifications?page=2',
            ],
        ]);
    }

    #[Test]
    public function it_gets_notification_by_identifier(): void
    {
        /** @var Notification $notification */
        $notification = NotificationFactory::createOne();
        $uri = '/api/notifications/' . $notification->getId();

        self::createClient()->request('GET', $uri);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertMatchesResourceItemJsonSchema(Notification::class);

        self::assertJsonContains([
            '@context' => '/api/contexts/Notification',
            '@id' => $uri,
            '@type' => 'Notification',
            'id' => $notification->getId(),
            'recipientEmail' => $notification->getRecipientEmail(),
            'subject' => $notification->getSubject(),
            'body' => $notification->getBody(),
            'status' => $notification->getStatus()->value,
            'createdAt' => $notification->getCreatedAt()->format(\DateTime::ATOM),
        ]);
    }

    #[Test]
    public function it_creates_notification(): void
    {
        $bodyRequest = [
            'recipientEmail' => 'test123@email.com',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ];

        $response = self::createClient()->request(
            method: 'POST',
            url: '/api/notifications',
            options: [
                'headers' => ['content-type' => 'application/ld+json'],
                'json' => $bodyRequest,
            ],
        );

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertMatchesRegularExpression('~^/api/notifications/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Notification::class);

        self::assertJsonContains([
            '@context' => '/api/contexts/Notification',
            '@type' => 'Notification',
            'recipientEmail' => 'test123@email.com',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ]);
    }

    #[Test]
    public function it_does_not_create_a_new_notification_with_invalid_data(): void
    {
        $bodyRequest = [
            'recipientEmail' => 'wrong email',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ];

        self::createClient()->request(
            method: 'POST',
            url: '/api/notifications',
            options: [
                'headers' => ['content-type' => 'application/ld+json'],
                'json' => $bodyRequest,
            ],
        );

        self::assertResponseStatusCodeSame(422);

        self::assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolation',
            '@type' => 'ConstraintViolation',
            'status' => 422,
            'detail' => 'recipientEmail: This value is not a valid email address.',
            'description' => 'recipientEmail: This value is not a valid email address.',
            'title' => 'An error occurred',
        ]);
    }
}
