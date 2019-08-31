<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\ForeignKeyColumnMismatch;
use PHPUnit\Framework\TestCase;

final class ForeignKeyColumnMismatchTest extends TestCase
{
    public function testConstructorFormatsMessageCorrectly(): void
    {
        $this->expectException(ForeignKeyColumnMismatch::class);
        $this->expectExceptionMessage('Column count in foreign key constraint (1) must match referenced column count (2)');

        throw new ForeignKeyColumnMismatch(1, 2);
    }
}
