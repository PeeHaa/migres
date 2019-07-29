<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Real;
use PHPUnit\Framework\TestCase;

class RealTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('real', (new Real())->toSql());
    }
}
