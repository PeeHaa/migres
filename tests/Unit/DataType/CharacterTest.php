<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Character;
use PHPUnit\Framework\TestCase;

class CharacterTest extends TestCase
{
    public function testToSqlWithoutLength(): void
    {
        $this->assertSame('character', (new Character())->toSql());
    }

    public function testToSqlWithLength(): void
    {
        $this->assertSame('character(12)', (new Character(12))->toSql());
    }
}
