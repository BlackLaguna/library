<?php

namespace Auth\Infrastructure\Api\Security\Authenticator;

use Auth\Domain\Service\TokenEncrypterService;
use Exception;

final readonly class RsaTokenEncrypter implements TokenEncrypterService
{
    private string $publicKey;
    private string $privateKey;

    public function __construct(
        string $publicKey,
        string $privateKey,
    ) {
        $this->publicKey = file_get_contents($publicKey);
        $this->privateKey = file_get_contents($privateKey);
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