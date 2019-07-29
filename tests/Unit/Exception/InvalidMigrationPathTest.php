<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\InvalidMigrationPath;
use PHPUnit\Framework\TestCase;

class InvalidMigrationPathTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('The migration path (`/migrations`) is invalid.');

        throw new InvalidMigrationPath('/migrations');
    }
}
