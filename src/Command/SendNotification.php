<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Validator\Constraints as Assert;

readonly class SendNotification
{
    public function __construct(
        #[Assert\NotBlank]
        public string $id
    ) {
    }
}
