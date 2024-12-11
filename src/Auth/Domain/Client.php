<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Client\ClientEmail;
use Auth\Domain\Client\ClientName;
use Auth\Domain\Client\ClientPassword;
use Auth\Domain\Client\ClientToken;
use Auth\Domain\Exception\AccessForbidden;
use Auth\Domain\Exception\ClientAlreadyExistsException;
use Auth\Domain\Exception\InvalidEmailException;
use Auth\Domain\Exception\PasswordMismatched;
use Auth\Domain\Exception\TokenExpireException;
use Auth\Domain\Service\ClientPasswordHasher;
use Auth\Domain\Service\IsClientExistService;
use Auth\Domain\Service\TokenEncrypterService;
use DateTimeImmutable;

class Client
{
    /** @var Role[] */
    private array $roles;

    public function __construct(
        private ClientEmail $email,
        private ClientName $name,
        private ClientPassword $password,
        Role ...$roles,
    ) {
        $this->roles = $roles;
    }

    /**
     * @throws ClientAlreadyExistsException
     */
    public static function createNewClient(
        ClientName $name,
        ClientEmail $email,
        ClientPassword $password,
        ClientPasswordHasher $hasher,
        IsClientExistService $clientExistService,
    ): self {
        if ($clientExistService->isClientExist($email)) {
            throw new ClientAlreadyExistsException();
        }

        $roles[] = Role::USER;

        return new self($email, $name, $hasher->hash($password), ...$roles);
    }

    /**
     * @throws PasswordMismatched
     * @throws AccessForbidden
     */
    public function createNewTokenForUser(
        ClientEmail $email,
        ClientPassword $password,
        TokenEncrypterService $tokenEncrypter,
        ClientPasswordHasher $passwordHasher
    ): ClientToken {
        if (!$this->hasRole(Role::USER)) {
            throw new AccessForbidden();
        }

        if (!$this->email->equals($email) || $this->password->equals($passwordHasher->hash($password))) {
            throw new PasswordMismatched();
        }

        return new ClientToken($tokenEncrypter->encrypt(
            sprintf('%s:%s', $this->email->email, (new DateTimeImmutable())->getTimestamp())
        ));
    }

    /**
     * @throws InvalidEmailException
     * @throws TokenExpireException
     */
    public static function extractClientEmailFromValidToken(
        ClientToken $token,
        TokenEncrypterService $tokenEncrypter
    ): ClientEmail {
        $tokenData = explode(':', $tokenEncrypter->decrypt($token->token));
        $email = ClientEmail::fromString($tokenData[0]);
        $tokenTimestamp = DateTimeImmutable::createFromFormat('U', $tokenData[1]);

        if (time() - $tokenTimestamp->getTimestamp() > 3600 * 3) {
            throw new TokenExpireException();
        }

        return $email;
    }

    public function hasRole(Role $role): bool
    {
        return in_array(
            needle: $role,
            haystack: $this->roles,
            strict: true
        );
    }

    public function getEmail(): ClientEmail
    {
        return $this->email;
    }

    public function getName(): ClientName
    {
        return $this->name;
    }

    public function getPassword(): ClientPassword
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}