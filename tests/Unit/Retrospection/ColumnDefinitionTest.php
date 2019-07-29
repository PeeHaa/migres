<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Retrospection;

use PeeHaa\Migres\Retrospection\ColumnDefinition;
use PHPUnit\Framework\TestCase;

class ColumnDefinitionTest extends TestCase
{
    private ?ColumnDefinition $definition;

    public function setUp(): void
    {
        $this->definition = ColumnDefinition::fromInformationSchemaRecord([
            'column_default'           => 'TheDefault',
            'is_nullable'              => 'YES',
            'data_type'                => 'character varying',
            'character_maximum_length' => 12,
            'numeric_precision'        => 14,
            'numeric_scale'            => 42,
        ]);
    }

    public function testGetDefaultValue(): void
    {
        $this->assertSame('TheDefault', $this->definition->getDefaultValue());
    }

    public function testIsNullable(): void
    {
        $this->assertTrue($this->definition->isNullable());
    }

    public function testGetDataType(): void
    {
        $this->assertSame('character varying', $this->definition->getDataType());
    }

    public function testGetMaximumLength(): void
    {
        $this->assertSame(12, $this->definition->getMaximumLength());
    }

    public function testGetNumericPrecision(): void
    {
        $this->assertSame(14, $this->definition->getNumericPrecision());
    }

    public function testGetNumericScale(): void
    {
        $this->assertSame(42, $this->definition->getNumericScale());
    }
}
