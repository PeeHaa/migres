<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Configuration;

use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Configuration\Database;
use PeeHaa\Migres\Configuration\MigrationPath;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    private ?Configuration $configuration;

    public function setUp(): void
    {
        $this->configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/migrations'),
            'Test\Foo\Bar\Migration',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );
    }

    public function testGetMigrationPath(): void
    {
        $this->assertSame(DATA_DIRECTORY . '/migrations', $this->configuration->getMigrationPath());
    }

    public function testGetNamespace(): void
    {
        $this->assertSame('Test\Foo\Bar\Migration', $this->configuration->getNamespace());
    }

    public function testGetDatabaseConfiguration(): void
    {
        $this->assertInstanceOf(Database::class, $this->configuration->getDatabaseConfiguration());
    }

    public function testFromArray(): void
    {
        $configuration = Configuration::fromArray([
            'migrationPath' => DATA_DIRECTORY . '/migrations',
            'namespace'     => 'Test\Foo\Bar\Migration',
            'database'      => [
                'name'     => 'test_db',
                'host'     => 'localhost',
                'port'     => 5432,
                'username' => 'username',
                'password' => 'password',
            ],
        ]);

        $this->assertSame(DATA_DIRECTORY . '/migrations', $this->configuration->getMigrationPath());
        $this->assertSame('Test\Foo\Bar\Migration', $this->configuration->getNamespace());
        $this->assertInstanceOf(Database::class, $this->configuration->getDatabaseConfiguration());
    }
}
