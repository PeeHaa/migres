<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Configuration;

use PeeHaa\Migres\Configuration\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private ?Database $configuration;

    public function setUp(): void
    {
        $this->configuration = new Database('test_db', 'localhost', 5432, 'username', 'password');
    }

    public function testGetName(): void
    {
        $this->assertSame('test_db', $this->configuration->getName());
    }

    public function testGetHost(): void
    {
        $this->assertSame('localhost', $this->configuration->getHost());
    }

    public function testGetPort(): void
    {
        $this->assertSame(5432, $this->configuration->getPort());
    }

    public function testGetUsername(): void
    {
        $this->assertSame('username', $this->configuration->getUsername());
    }

    public function testGetPassword(): void
    {
        $this->assertSame('password', $this->configuration->getPassword());
    }

    public function testFromArray(): void
    {
        $configuration = Database::fromArray([
            'name'     => 'test_db',
            'host'     => 'localhost',
            'port'     => 5432,
            'username' => 'username',
            'password' => 'password',
        ]);

        $this->assertSame('test_db', $configuration->getName());
        $this->assertSame('localhost', $configuration->getHost());
        $this->assertSame(5432, $configuration->getPort());
        $this->assertSame('username', $configuration->getUsername());
        $this->assertSame('password', $configuration->getPassword());
    }
}
