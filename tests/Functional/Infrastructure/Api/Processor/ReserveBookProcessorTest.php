<?php

declare(strict_types=1);

namespace Tests\Functional\Infrastructure\Api\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\Shared\AuthApiTestCase;
use Tests\Utils\TestDB;

class ReserveBookProcessorTest extends AuthApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testReserveBook(): void
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

        $this->getClient()->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/books/e28808b6-892b-55dd-a9d5-85f6c7c360c4/reserve',
            parameters: [
                'reserveFrom' => 1,
                'reserveTo' => 2,
            ],
        );
        static::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        TestDB::assertRecordExists(
            'books',
            [
                'name' => 'testName',
                'total_quantity' => 100,
                'available_quantity' => 99,
                'author_first_name' => 'testFirstName',
                'author_last_name' => 'testLastName',
                'description' => 'testDescription',
            ]
        );
        TestDB::assertRecordExists(
            'reservations',
            [
                'book_id' => 'e28808b6-892b-55dd-a9d5-85f6c7c360c4',
                'client_id' => AuthApiTestCase::CLIENT_ID,
                'status' => 'NEW',
                'date_from' => '1970-01-01 00:00:01',
                'date_to' => '1970-01-01 00:00:02',
            ]
        );
    }
}