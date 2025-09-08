<?php

namespace App\Entity;

use App\Enum\NotificationStatus;

interface NotificationInterface
{
    public function getId(): ?int;
    public function getRecipientEmail(): string;
    public function getSubject(): string;
    public function getBody(): string;
    public function getStatus(): NotificationStatus;
    public function getCreatedAt(): \DateTimeImmutable;
    public function getSentAt(): ?\DateTimeImmutable;
    public function getStatusAsString(): string;
    public function setRecipientEmail(string $recipientEmail): void;
    public function setSubject(string $subject): void;
    public function setBody(string $body): void;
    public function setStatus(NotificationStatus $status): void;
    public function setCreatedAt(\DateTimeImmutable $createdAt): void;
    public function setSentAt(?\DateTimeImmutable $sentAt): void;
    public function setStatusAsString(string $stateAsString): void;
}
