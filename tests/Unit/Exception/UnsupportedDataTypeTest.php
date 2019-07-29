<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\UnsupportedDataType;
use PHPUnit\Framework\TestCase;

class UnsupportedDataTypeTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Unsupported data type (`unsupported`) from specification unsupported(12)');

        throw new UnsupportedDataType('unsupported', 'unsupported(12)');
    }
}
