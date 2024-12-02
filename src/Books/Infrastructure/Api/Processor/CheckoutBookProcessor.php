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

        $this->commandBus->dispatch(
            new CheckoutBookCommand(
                $
            )
        )
    }
}