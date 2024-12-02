<?php

declare(strict_types=1);

namespace Tests\Unit\Books\Infrastructure\Api\Processor;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Utils\TestDB;

class MakeBookUnavailableProcessorTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        self::createClient();
        TestDB::$connection = $this->getContainer()->get(Connection::class);
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

        $this->getClient()->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/books/e28808b6-892b-55dd-a9d5-85f6c7c360c4/unavailable',
        );

        static::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        TestDB::assertRecordExists(
            'books',
            [
                'name' => 'testName',
                'total_quantity' => 0,
                'available_quantity' => 0,
                'author_first_name' => 'testFirstName',
                'author_last_name' => 'testLastName',
                'description' => 'testDescription',
            ]
        );
    }
}