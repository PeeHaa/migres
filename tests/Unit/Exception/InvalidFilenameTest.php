<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\InvalidFilename;
use PHPUnit\Framework\TestCase;

class InvalidFilenameTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Invalid filename (`test.php`) for a migration file.');

        throw new InvalidFilename('test.php');
    }
}
