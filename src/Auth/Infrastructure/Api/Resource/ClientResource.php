<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Auth\Infrastructure\Api\Processor\RegisterProcessor;
use Auth\Infrastructure\Api\Resource\Request\LoginRequest;
use Auth\Infrastructure\Api\Resource\Request\RegisterRequest;
use Auth\Infrastructure\Doctrine\Entity\Client;

#[ApiResource(stateOptions: new Options(entityClass: Client::class))]
#[Post(
    uriTemplate: '/login',
    input: LoginRequest::class,
    processor: LoginRequest::class,
)]
#[Post(
    uriTemplate: '/register',
    input: RegisterRequest::class,
    processor: RegisterProcessor::class,
)]
readonly class ClientResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public string $uuid,
        public string $email,
        public string $name,
    ) {
    }
}