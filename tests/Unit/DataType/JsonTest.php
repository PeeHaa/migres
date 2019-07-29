<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('json', (new Json())->toSql());
    }
}
