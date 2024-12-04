<?php

declare(strict_types=1);

namespace Tests\Functional\Infrastructure\Api\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\Shared\AuthApiTestCase;
use Tests\Utils\TestDB;

class CreateBookProcessorTest extends AuthApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateBook(): void
    {
        $this->getClient()->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/books',
            parameters: [
                'author' => [
                    'firstName' => 'testFirstName',
                    'lastName' => 'testLastName',
                ],
                'name' => 'testName',
                'totalQuantity' => 100,
                'description' => 'testDescription',
            ],
        );

        static::assertResponseStatusCodeSame(Response::HTTP_CREATED); // TODO 201 response
        TestDB::assertRecordExists(
            'books',
            [
                'name' => 'testName',
                'total_quantity' => 100,
                'available_quantity' => 100,
                'author_first_name' => 'testFirstName',
                'author_last_name' => 'testLastName',
                'description' => 'testDescription',
            ]
        );
    }
}