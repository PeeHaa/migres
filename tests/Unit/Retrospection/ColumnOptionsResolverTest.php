<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Retrospection;

use PeeHaa\Migres\Retrospection\ColumnDefinition;
use PeeHaa\Migres\Retrospection\ColumnInformation;
use PeeHaa\Migres\Retrospection\ColumnOptionsResolver;
use PeeHaa\Migres\Retrospection\Sequence;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class ColumnOptionsResolverTest extends TestCase
{
    private ?ColumnOptionsResolver $resolver;

    public function setUp(): void
    {
        $this->resolver = new ColumnOptionsResolver(new Sequence());
    }

    public function testResolveSetNullable(): void
    {
        $options = $this->resolver->resolve(
            new ColumnInformation(
                new Label('table_name'),
                new Label('column_name'),
                ColumnDefinition::fromInformationSchemaRecord([
                    'column_default'           => null,
                    'is_nullable'              => 'NO',
                    'data_type'                => 'integer',
                    'character_maximum_length' => 12,
                    'numeric_precision'        => null,
                    'numeric_scale'            => null,
                ]),
            ),
        );

        $this->assertFalse($options->isNullable());
    }

    public function testResolveDoesNotSetDefaultValueForBigSerialColumn(): void
    {
        $options = $this->resolver->resolve(
            new ColumnInformation(
                new Label('table_name'),
                new Label('column_name'),
                ColumnDefinition::fromInformationSchemaRecord([
                    'column_default'           => 'nextval(\'table_name_column_name_seq\'::regclass)',
                    'is_nullable'              => 'NO',
                    'data_type'                => 'bigint',
                    'character_maximum_length' => null,
                    'numeric_precision'        => null,
                    'numeric_scale'            => null,
                ]),
            ),
        );

        $this->assertFalse($options->hasDefault());
    }

    public function testResolveDoesNotSetDefaultValueForSerialColumn(): void
    {
        $options = $this->resolver->resolve(
            new ColumnInformation(
                new Label('table_name'),
                new Label('column_name'),
                ColumnDefinition::fromInformationSchemaRecord([
                    'column_default'           => 'nextval(\'table_name_column_name_seq\'::regclass)',
                    'is_nullable'              => 'NO',
                    'data_type'                => 'integer',
                    'character_maximum_length' => null,
                    'numeric_precision'        => null,
                    'numeric_scale'            => null,
                ]),
            ),
        );

        $this->assertFalse($options->hasDefault());
    }

    public function testResolveDoesNotSetDefaultValueForSmallSerialColumn(): void
    {
        $options = $this->resolver->resolve(
            new ColumnInformation(
                new Label('table_name'),
                new Label('column_name'),
                ColumnDefinition::fromInformationSchemaRecord([
                    'column_default'           => 'nextval(\'table_name_column_name_seq\'::regclass)',
                    'is_nullable'              => 'NO',
                    'data_type'                => 'smallint',
                    'character_maximum_length' => null,
                    'numeric_precision'        => null,
                    'numeric_scale'            => null,
                ]),
            ),
        );

        $this->assertFalse($options->hasDefault());
    }

    public function testResolveSetsDefaultValue(): void
    {
        $options = $this->resolver->resolve(
            new ColumnInformation(
                new Label('table_name'),
                new Label('column_name'),
                ColumnDefinition::fromInformationSchemaRecord([
                    'column_default'           => 'TheDefault',
                    'is_nullable'              => 'NO',
                    'data_type'                => 'character varying',
                    'character_maximum_length' => 16,
                    'numeric_precision'        => null,
                    'numeric_scale'            => null,
                ]),
            ),
        );

        $this->assertTrue($options->hasDefault());
    }
}
