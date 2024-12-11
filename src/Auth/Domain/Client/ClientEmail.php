<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

use Auth\Domain\Exception\InvalidEmailException;

final readonly class ClientEmail
{
    /**
     * @throws InvalidEmailException
     */
    public function __construct(
        public string $email,
    ) {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
    }

    /**
     * @throws InvalidEmailException
     */
    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function equals(ClientEmail $email): bool
    {
        return $this->email === $email->email;
    }
}