<?php

declare(strict_types=1);

namespace Auth\Domain;

final readonly class Password
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

    public function equals(self $password): bool
    {
        return $this->password === $password->password;
    }
}