<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

use Auth\Domain\Exception\InvalidEmailException;
use Auth\Domain\PasswordLengthIncorrect;

final readonly class ClientPassword
{
    /**
     * @throws PasswordLengthIncorrect
     */
    public function __construct(public string $password)
    {
        $passwordLength = mb_strlen($this->password);

        if (9 <= $passwordLength && $passwordLength <= 256) {
            throw new PasswordLengthIncorrect();
        }
    }


    /**
     * @throws PasswordLengthIncorrect
     */
    public static function fromString(string $password): self
    {
        return new self($password);
    }

    public function equals(self $password): bool
    {
        return $this->password === $password->password;
    }
}