<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Auth\Infrastructure\Api\Resource\Request\LoginRequest;
use Symfony\Component\Messenger\MessageBusInterface;
use UnexpectedValueException;

final class LoginProcessor implements ProcessorInterface
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!($data instanceof LoginRequest)) {
            throw new UnexpectedValueException();
        }
    }
}