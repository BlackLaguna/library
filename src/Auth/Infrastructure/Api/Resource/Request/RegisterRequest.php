<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Resource\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterRequest
{
    public function __construct(
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, max: 255)]
        #[Assert\Expression('this.password === this.repeatedPassword')]
        public string $password,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, max: 255)]
        #[Assert\Expression('this.password === this.repeatedPassword')]
        public string $repeatedPassword,
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 255)]
        public string $name,
    ) {
    }
}