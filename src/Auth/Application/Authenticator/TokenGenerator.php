<?php

namespace Auth\Application\Authenticator;

use Exception;

final class TokenGenerator
{
    public function __construct(
        private string $publicKey,
        private string $privateKey,
    ) {
    }

    public function encrypt(string $data): string
    {
        $encrypted = '';
        if (!openssl_public_encrypt($data, $encrypted, $this->publicKey)) {
            throw new Exception("Encryption failed.");
        }

        return base64_encode($encrypted);
    }

    public function decrypt(string $encryptedData): string
    {
        $decrypted = '';
        $encryptedData = base64_decode($encryptedData);

        if (!openssl_private_decrypt($encryptedData, $decrypted, $this->privateKey)) {
            throw new Exception("Decryption failed.");
        }

        return $decrypted;
    }
}