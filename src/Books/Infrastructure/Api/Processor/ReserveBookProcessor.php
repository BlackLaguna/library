<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\ReserveBookCommand;
use Books\Infrastructure\Api\Resource\Request\ReserveBookRequest;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class ReserveBookProcessor implements ProcessorInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!($data instanceof ReserveBookRequest)) {
            throw new InvalidArgumentException();
        }

        $bookId = $uriVariables['id'];
        $clientId = $this->security->getUser()->getUserIdentifier();

        $this->commandBus->dispatch(new ReserveBookCommand(
            bookId: $bookId,
            clientId: $clientId,
            reserveFrom: $data->reserveFrom,
            reserveTo: $data->reserveTo,
        ));
    }
}