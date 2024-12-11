<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Security;

use Auth\Domain\Client\ClientPassword;
use Auth\Domain\PasswordLengthIncorrect;
use Auth\Domain\Service\ClientPasswordHasher;

final class SaltClientPasswordHasher implements ClientPasswordHasher
{
    /** @throws PasswordLengthIncorrect */
    public function hash(ClientPassword $password): ClientPassword
    {
        return ClientPassword::fromString(password_hash($password->password, PASSWORD_DEFAULT));
    }
}