<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateNotification
{
    public function __construct(
        #[Assert\Email]
        #[Assert\NotBlank]
        public string $recipientEmail,

        #[Assert\NotBlank]
        public string $subject,

        #[Assert\NotBlank]
        public string $body
    ) {
    }
}
