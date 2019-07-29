<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Retrospection;

use PeeHaa\Migres\Retrospection\ColumnDefinition;
use PeeHaa\Migres\Retrospection\ColumnInformation;
use PeeHaa\Migres\Retrospection\Sequence;
use PHPUnit\Framework\TestCase;

class SequenceTest extends TestCase
{
    public function testIsColumnUsingSequenceReturnsFalse(): void
    {
        $columnInformation = new ColumnInformation(
            'table_name',
            'column_name',
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'unsupported',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertFalse((new Sequence())->isColumnUsingSequence($columnInformation));
    }

    public function testIsColumnUsingSequenceReturnsTrue(): void
    {
        $columnInformation = new ColumnInformation(
            'table_name',
            'column_name',
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => "nextval('table_name_column_name_seq'::regclass)",
                'is_nullable'              => 'NO',
                'data_type'                => 'unsupported',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertTrue((new Sequence())->isColumnUsingSequence($columnInformation));
    }
}
