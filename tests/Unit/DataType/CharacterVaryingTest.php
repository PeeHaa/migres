<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\CharacterVarying;
use PHPUnit\Framework\TestCase;

class CharacterVaryingTest extends TestCase
{
    public function testToSqlWithoutLength(): void
    {
        $this->assertSame('character varying', (new CharacterVarying())->toSql());
    }

    public function testToSqlWithLength(): void
    {
        $this->assertSame('character varying(12)', (new CharacterVarying(12))->toSql());
    }
}
