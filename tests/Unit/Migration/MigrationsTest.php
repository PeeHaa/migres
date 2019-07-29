<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Migration;

use PeeHaa\Migres\Migration;
use PeeHaa\Migres\Migration\Migrations;
use PHPUnit\Framework\TestCase;

class MigrationsTest extends TestCase
{
    public function testIteratorImplementation(): void
    {
        $migrations = new Migrations(
            new Migration('Name1', 'name_1.php', 'Namespace', new \DateTimeImmutable()),
            new Migration('Name2', 'name_2.php', 'Namespace', new \DateTimeImmutable()),
        );

        foreach ($migrations as $i => $migration) {
            $this->assertSame('Name' . ($i + 1), $migration->getName());
        }
    }
}
