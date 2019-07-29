<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\JsonB;
use PHPUnit\Framework\TestCase;

class JsonBTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('jsonb', (new JsonB())->toSql());
    }
}
