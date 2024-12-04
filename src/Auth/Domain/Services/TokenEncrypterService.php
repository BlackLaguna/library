<?php

declare(strict_types=1);

namespace Auth\Domain\Services;

interface TokenEncrypterService
{
    public function encrypt(string $data): string;
    public function decrypt(string $token): string;
}