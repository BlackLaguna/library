<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Doctrine\Repository;

use Auth\Domain\Client;
use Auth\Domain\Client\ClientName;
use Auth\Domain\Client\ClientPassword;
use Auth\Domain\ClientRepository;
use Auth\Domain\Client\ClientEmail;
use Auth\Domain\Exception\InvalidEmailException;
use Auth\Domain\PasswordLengthIncorrect;
use Auth\Domain\Role;
use Auth\Infrastructure\Doctrine\Entity\Client as ClientEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class DoctrineClientRepository implements ClientRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }


    /**
     * @throws PasswordLengthIncorrect
     * @throws InvalidEmailException
     */
    public function getById(ClientEmail $clientEmail): Client
    {
        $clientEntity = $this->entityManager->find(ClientEntity::class, $clientEmail->email);

        if (null === $clientEntity) {
            throw new NotFoundHttpException();
        }

        $roles = array_map(static fn (string $role) => Role::from($role), $clientEntity->getRoles());

        return new Client(
            ClientEmail::fromString($clientEntity->getEmail()),
            ClientName::fromString($clientEntity->getName()),
            ClientPassword::fromString($clientEntity->getPassword()),
            ...$roles,
        );
    }

    public function save(Client $client): void
    {
        $clientEntity = new ClientEntity(
            email: $client->getEmail()->email,
            name: $client->getName()->name,
            password: $client->getPassword()->password,
            roles: array_reduce(
                $client->getRoles(),
                static fn (array $roles, Role $role): array => [...$roles, $role->value],
                []
            ),
        );
        $this->entityManager->getConnection()->insert('book_clients', ['email' => $clientEntity->getEmail()]);
        $this->entityManager->persist($clientEntity);
        $this->entityManager->flush();
    }
}