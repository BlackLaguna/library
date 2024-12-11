<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Auth\Infrastructure\Api\Processor\ClientProvider;
use Auth\Infrastructure\Api\Processor\LoginProcessor;
use Auth\Infrastructure\Api\Processor\RegisterProcessor;
use Auth\Infrastructure\Api\Resource\Request\LoginRequest;
use Auth\Infrastructure\Api\Resource\Request\RegisterRequest;
use Auth\Infrastructure\Api\Resource\Response\ClientToken;
use Auth\Infrastructure\Doctrine\Entity\Client;

#[ApiResource(stateOptions: new Options(entityClass: Client::class))]
#[Get(
    uriTemplate: '/me',
    outputFormats: 'json',
    provider: ClientProvider::class,
)]
#[Post(
    uriTemplate: '/login',
    outputFormats: 'json',
    input: LoginRequest::class,
    output: ClientToken::class,
    processor: LoginProcessor::class,
)]
#[Post(
    uriTemplate: '/register',
    outputFormats: 'json',
    input: RegisterRequest::class,
    output: ClientToken::class,
    processor: RegisterProcessor::class,
)]
readonly class ClientResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public string $email,
        public string $name,
    ) {
    }
}