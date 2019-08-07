<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Retrospection;

use PeeHaa\Migres\Retrospection\ColumnDefinition;
use PeeHaa\Migres\Retrospection\ColumnInformation;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class ColumnInformationTest extends TestCase
{
    private ?ColumnInformation $information;

    public function setUp(): void
    {
        $this->information = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => 'TheDefault',
                'is_nullable'              => 'YES',
                'data_type'                => 'character varying',
                'character_maximum_length' => 12,
                'numeric_precision'        => 14,
                'numeric_scale'            => 42,
            ]),
        );
    }

    public function testGetTableName(): void
    {
        $this->assertSame('table_name', $this->information->getTableName()->toString());
    }

    public function testGetColumnName(): void
    {
        $this->assertSame('column_name', $this->information->getColumnName()->toString());
    }

    public function testGetColumnDefinition(): void
    {
        $this->assertInstanceOf(ColumnDefinition::class, $this->information->getColumnDefinition());
    }
}
