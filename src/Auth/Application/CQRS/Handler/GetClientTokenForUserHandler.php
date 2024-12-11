<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Handler;

use Auth\Application\CQRS\Query\GetClientTokenForUserQuery;
use Auth\Domain\Client\ClientEmail;
use Auth\Domain\Client\ClientPassword;
use Auth\Domain\Client\ClientToken;
use Auth\Domain\ClientRepository;
use Auth\Domain\Exception\AccessForbidden;
use Auth\Domain\Exception\InvalidEmailException;
use Auth\Domain\Exception\PasswordMismatched;
use Auth\Domain\PasswordLengthIncorrect;
use Auth\Domain\Service\ClientPasswordHasher;
use Auth\Domain\Service\TokenEncrypterService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetClientTokenForUserHandler
{
    public function __construct(
        private ClientRepository $clientRepository,
        private TokenEncrypterService $tokenEncrypterService,
        private ClientPasswordHasher $passwordHasher,
    ) {
    }

    /**
     * @throws PasswordLengthIncorrect
     * @throws InvalidEmailException
     * @throws PasswordMismatched
     * @throws AccessForbidden
     */
    public function __invoke(GetClientTokenForUserQuery $query): ClientToken
    {
        $client = $this->clientRepository->getById($clientEmail = ClientEmail::fromString($query->email));

        return $client->createNewTokenForUser(
            email: $clientEmail,
            password: ClientPassword::fromString($query->password),
            tokenEncrypter: $this->tokenEncrypterService,
            passwordHasher: $this->passwordHasher,
        );
    }
}