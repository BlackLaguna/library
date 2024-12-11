<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\CheckoutBookCommand;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;

final class CheckoutBookProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $bookId = $uriVariables['id'];
        $clientId = $this->security->getUser()->getUserIdentifier();

        $this->commandBus->dispatch(new CheckoutBookCommand(bookId: $bookId, clientId: $clientId));
    }
}