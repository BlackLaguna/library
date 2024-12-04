<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Auth\Infrastructure\Api\Resource\Request\RegisterRequest;
use Auth\Infrastructure\Doctrine\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Uid\Uuid;
use UnexpectedValueException;

//use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final readonly class RegisterProcessor implements ProcessorInterface
{
    public function __construct(
//        private PasswordHasherInterface $hasher,
        private EntityManagerInterface $entityManager,
        private UserAuthenticatorInterface $authenticator
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!($data instanceof RegisterRequest)) {
            throw new UnexpectedValueException();
        }

        $hashedPassword = $this->hasher->hash($data->password);
        $client = new Client(
            uuid: Uuid::v4(),
            name: $data->name,
            email: $data->email,
            password: $hashedPassword,
            roles: ['ROLE_CLIENT'],
        );
        $this->entityManager->persist($client);
        $this->authenticator->authenticateUser($client);
    }
}