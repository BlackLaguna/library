<?php

declare(strict_types=1);

namespace Tests\Functional\Infrastructure\Api\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\Shared\AuthApiTestCase;
use Tests\Utils\TestDB;

class ReturnBookProcessorTest extends AuthApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnBook(): void
    {
        TestDB::insertRecord(
            'books',
            [
                'uuid' => 'e28808b6-892b-55dd-a9d5-85f6c7c360c4',
                'name' => 'testName',
                'total_quantity' => 100,
                'available_quantity' => 99,
                'author_first_name' => 'testFirstName',
                'author_last_name' => 'testLastName',
                'description' => 'testDescription',
            ]
        );
        TestDB::insertRecord(
            'reservations',
            [
                'uuid' => 'e28808b6-892b-55dd-a9d5-85f6c7c360c5',
                'book_id' => 'e28808b6-892b-55dd-a9d5-85f6c7c360c4',
                'client_id' => AuthApiTestCase::CLIENT_ID,
                'status' => 'NEW',
                'date_from' => '2023-12-02 15:45:30',
                'date_to' => '2023-12-03 15:45:30',
            ]
        );


        $this->getClient()->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/books/e28808b6-892b-55dd-a9d5-85f6c7c360c4/return',
        );

        static::assertResponseStatusCodeSame(Response::HTTP_CREATED);
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
        TestDB::assertRecordExists(
            'reservations',
            [
                'uuid' => 'e28808b6-892b-55dd-a9d5-85f6c7c360c5',
                'book_id' => 'e28808b6-892b-55dd-a9d5-85f6c7c360c4',
                'client_id' => AuthApiTestCase::CLIENT_ID,
                'status' => 'FINISHED',
                'date_from' => '2023-12-02 15:45:30',
                'date_to' => '2023-12-03 15:45:30',
            ]
        );
    }
}