<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Configuration;

use PeeHaa\Migres\Configuration\MigrationPath;
use PeeHaa\Migres\Exception\InvalidMigrationPath;
use PHPUnit\Framework\TestCase;

class MigrationPathTest extends TestCase
{
    public function testConstructorThrowsOnInvalidPath(): void
    {
        $this->expectException(InvalidMigrationPath::class);
        $this->expectExceptionMessage(
            'The migration path (`' . DATA_DIRECTORY . '/invalid-migration-path`) is invalid.'
        );

        new MigrationPath(DATA_DIRECTORY . '/invalid-migration-path');
    }

    public function testGetPath(): void
    {
        $this->assertSame(
            DATA_DIRECTORY . '/migrations',
            (new MigrationPath(DATA_DIRECTORY . '/migrations'))->getPath(),
        );
    }
}
