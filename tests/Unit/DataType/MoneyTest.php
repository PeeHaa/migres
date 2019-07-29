<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('money', (new Money())->toSql());
    }
}
