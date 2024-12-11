<?php

declare(strict_types=1);

namespace Auth\Domain\Service;

interface TokenEncrypterService
{
    public function encrypt(string $data): string;
    public function decrypt(string $token): string;
}