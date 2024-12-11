<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Handler;

use Auth\Application\CQRS\Command\RegisterClientCommand;
use Auth\Domain\Client;
use Auth\Domain\Client\ClientEmail;
use Auth\Domain\Client\ClientName;
use Auth\Domain\Client\ClientPassword;
use Auth\Domain\ClientRepository;
use Auth\Domain\Exception\ClientAlreadyExistsException;
use Auth\Domain\Exception\InvalidEmailException;
use Auth\Domain\PasswordLengthIncorrect;
use Auth\Domain\Service\ClientPasswordHasher;
use Auth\Domain\Service\IsClientExistService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RegisterClientHandler
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ClientPasswordHasher $hasher,
        private IsClientExistService $isClientExistService,
    ) {
    }

    /**
     * @throws PasswordLengthIncorrect
     * @throws InvalidEmailException
     * @throws ClientAlreadyExistsException
     */
    public function __invoke(RegisterClientCommand $command): void
    {
        $client = Client::createNewClient(
            name: ClientName::fromString($command->name),
            email: ClientEmail::fromString($command->email),
            password: ClientPassword::fromString($command->password),
            hasher: $this->hasher,
            clientExistService: $this->isClientExistService,
        );
        $this->clientRepository->save($client);
    }
}