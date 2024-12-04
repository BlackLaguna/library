<?php

declare(strict_types=1);

namespace Tests\Functional\Shared;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\DBAL\Connection;
use Tests\Utils\TestDB;

abstract class AuthApiTestCase extends ApiTestCase
{
    public const CLIENT_ID = '6229d52f-1fff-4092-ae12-3bf3e408e1c5';

    public function setUp(): void
    {
        parent::setUp();
        self::createClient();
        TestDB::$connection = $this->getContainer()->get(Connection::class);

        TestDB::insertRecord(
            'clients',
            [
                'uuid' => self::CLIENT_ID,
                'name' => 'test',
                'email' => 'test@test.com',
                'password' => 'test',
            ]
        );

        TestDB::insertRecord(
            'book_clients',
            [
                'uuid' => self::CLIENT_ID,
            ]
        );
    }
}