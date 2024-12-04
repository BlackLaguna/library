<?php

declare(strict_types=1);

namespace Tests\Functional\Infrastructure\Api\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\Shared\AuthApiTestCase;
use Tests\Utils\TestDB;

class ChangeDescriptionBookProcessorTest extends AuthApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testChangeBookDescription(): void
    {
        TestDB::insertRecord(
            'books',
            [
                'uuid' => 'e28808b6-892b-55dd-a9d5-85f6c7c360c4',
                'name' => 'testName',
                'total_quantity' => 100,
                'available_quantity' => 100,
                'author_first_name' => 'testFirstName',
                'author_last_name' => 'testLastName',
                'description' => 'testDescription',
            ]
        );
        TestDB::assertRecordMissing(
            'books',
            [
                'name' => 'testName',
                'total_quantity' => 100,
                'available_quantity' => 100,
                'author_first_name' => 'testFirstName',
                'author_last_name' => 'testLastName',
                'description' => 'testNewDescription',
            ]
        );

        $this->getClient()->jsonRequest(
            method: Request::METHOD_PUT,
            uri: '/api/books/e28808b6-892b-55dd-a9d5-85f6c7c360c4/description',
            parameters: ['newDescription' => 'testNewDescription'],
        );

        static::assertResponseStatusCodeSame(Response::HTTP_OK);
        TestDB::assertRecordExists(
            'books',
            [
                'name' => 'testName',
                'total_quantity' => 100,
                'available_quantity' => 100,
                'author_first_name' => 'testFirstName',
                'author_last_name' => 'testLastName',
                'description' => 'testNewDescription',
            ]
        );
    }
}