<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

final readonly class ClientId
{
    public function __construct(
        public ClientEmail $clientEmail,
        public ClientPassword $clientPassword,
    ) {
    }

    public static function createNew(ClientEmail $clientEmail, ClientPassword $clientPassword): self
    {
        return new self($clientEmail, $clientPassword);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->clientEmail->email,
            $this->clientPassword->password,
            time(),
        );
    }
}