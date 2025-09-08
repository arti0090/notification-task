<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Symfony\Action\NotExposedAction;
use App\Command\CreateNotification;
use App\Controller\SendNotificationController;
use App\Enum\NotificationStatus;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\Table(name: 'notification')]
#[ApiResource(operations: [
    new GetCollection(),
    new Get(),
    new Post(
        uriTemplate: '/notifications',
        status: 201,
        input: CreateNotification::class,
        output: Notification::class,
        messenger: true,
        name: 'app_notification_create',
    ),
    new Patch(
        uriTemplate: '/notifications/{id}/send',
        status: 202,
        controller: SendNotificationController::class,
        input: false,
        output: false,
        read: false,
        name: 'app_notification_send',
    ),
    new Delete(
        status: 404,
        controller: NotExposedAction::class,
        openapi: false,
        read: false,
    ),
])]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email]
    #[Assert\NotBlank]
    private string $recipientEmail;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $subject;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private string $body;

    #[ORM\Column(enumType: NotificationStatus::class)]
    private NotificationStatus $status = NotificationStatus::Pending;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $sentAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getRecipientEmail(): string { return $this->recipientEmail; }
    public function getSubject(): string { return $this->subject; }
    public function getBody(): string { return $this->body; }
    public function getStatus(): NotificationStatus { return $this->status; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getSentAt(): ?\DateTimeImmutable { return $this->sentAt; }

    public function setRecipientEmail(string $recipientEmail): void
    {
        $this->recipientEmail = $recipientEmail;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function setStatus(NotificationStatus $status): void
    {
        $this->status = $status;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setSentAt(?\DateTimeImmutable $sentAt): void
    {
        $this->sentAt = $sentAt;
    }
}
