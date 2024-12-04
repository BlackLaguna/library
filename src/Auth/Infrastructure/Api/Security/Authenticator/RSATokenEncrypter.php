<?php

namespace Auth\Infrastructure\Api\Security\Authenticator;

use Auth\Domain\Services\TokenEncrypterService;
use Exception;

final readonly class RSATokenEncrypter implements TokenEncrypterService
{
    public function __construct(
        private string $publicKey,
        private string $privateKey,
    ) {
    }

    /**
     * @throws Exception
     */
    public function encrypt(string $data): string
    {
        $encrypted = '';

        if (!openssl_public_encrypt($data, $encrypted, $this->publicKey)) {
            throw new Exception("Encryption failed.");
        }

        return base64_encode($encrypted);
    }

    public function decrypt(string $token): string
    {
        $decrypted = '';

        $token = base64_decode($token);

        if (!openssl_private_decrypt($token, $decrypted, $this->privateKey)) {
            throw new Exception("Decryption failed.");
        }

        return $decrypted;
    }
}