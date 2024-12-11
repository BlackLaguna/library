<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Resource\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoginRequest
{
    public function __construct(
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, max: 255)]
        public string $password,
    ) {
    }
}