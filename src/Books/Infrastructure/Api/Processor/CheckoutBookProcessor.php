<?php

declare(strict_types=1);

namespace Books\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Books\Application\CQRS\Command\CheckoutBookCommand;
use Books\Infrastructure\Api\Resource\Request\CheckoutBookRequest;
use Symfony\Component\Messenger\MessageBusInterface;
use UnexpectedValueException;

final class CheckoutBookProcessor implements ProcessorInterface
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!($data instanceof CheckoutBookRequest)) {
            throw new UnexpectedValueException();
        }

        $bookId = $uriVariables['uuid'];
        $clientId = '6229d52f-1fff-4092-ae12-3bf3e408e1c5';

        $this->commandBus->dispatch(
            new CheckoutBookCommand(
                bookId: $bookId,
                clientid: $clientId,
            )
        );
    }
}