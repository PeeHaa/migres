<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit;

use PeeHaa\Migres\Migration;
use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    private ?Migration $migration;

    public function setUp(): void
    {
        $this->migration = new Migration(
            'TheName',
            'the_name.php',
            'TheName',
            new \DateTimeImmutable('2019-01-01 18:18:18'),
        );
    }

    public function testGetName(): void
    {
        $this->assertSame('TheName', $this->migration->getName());
    }

    public function testGetFilename(): void
    {
        $this->assertSame('the_name.php', $this->migration->getFilename());
    }

    public function testGetFullyQualifiedName(): void
    {
        $this->assertSame('TheName', $this->migration->getFullyQualifiedName());
    }

    public function testGetTimestamp(): void
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->migration->getTimestamp());
    }

    public function testGetActions(): void
    {
        $this->assertCount(0, $this->migration->getActions());
    }
}
