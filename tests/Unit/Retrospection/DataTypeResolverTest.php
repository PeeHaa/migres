<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Retrospection;

use PeeHaa\Migres\DataType\BigInt;
use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\DataType\Bit;
use PeeHaa\Migres\DataType\BitVarying;
use PeeHaa\Migres\DataType\Boolean;
use PeeHaa\Migres\DataType\Box;
use PeeHaa\Migres\DataType\ByteA;
use PeeHaa\Migres\DataType\Character;
use PeeHaa\Migres\DataType\CharacterVarying;
use PeeHaa\Migres\DataType\Cidr;
use PeeHaa\Migres\DataType\Circle;
use PeeHaa\Migres\DataType\Date;
use PeeHaa\Migres\DataType\DoublePrecision;
use PeeHaa\Migres\DataType\FloatType;
use PeeHaa\Migres\DataType\Inet;
use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\DataType\Json;
use PeeHaa\Migres\DataType\JsonB;
use PeeHaa\Migres\DataType\Line;
use PeeHaa\Migres\DataType\Lseg;
use PeeHaa\Migres\DataType\MacAddr;
use PeeHaa\Migres\DataType\Money;
use PeeHaa\Migres\DataType\Numeric;
use PeeHaa\Migres\DataType\Path;
use PeeHaa\Migres\DataType\Point;
use PeeHaa\Migres\DataType\Polygon;
use PeeHaa\Migres\DataType\Serial;
use PeeHaa\Migres\DataType\SmallInt;
use PeeHaa\Migres\DataType\SmallSerial;
use PeeHaa\Migres\DataType\Text;
use PeeHaa\Migres\DataType\TimestampWithoutTimezone;
use PeeHaa\Migres\DataType\TimestampWithTimezone;
use PeeHaa\Migres\DataType\TimeWithoutTimezone;
use PeeHaa\Migres\DataType\TimeWithTimezone;
use PeeHaa\Migres\DataType\Uuid;
use PeeHaa\Migres\Exception\UnsupportedDataType;
use PeeHaa\Migres\Retrospection\ColumnDefinition;
use PeeHaa\Migres\Retrospection\ColumnInformation;
use PeeHaa\Migres\Retrospection\DataTypeResolver;
use PeeHaa\Migres\Retrospection\Sequence;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DataTypeResolverTest extends TestCase
{
    private ?DataTypeResolver $resolver;

    public function setUp(): void
    {
        $this->resolver = new DataTypeResolver(new Sequence());
    }

    public function testResolveResolvesBigSerial(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => 'nextval(\'table_name_column_name_seq\'::regclass)',
                'is_nullable'              => 'NO',
                'data_type'                => 'bigint',
                'character_maximum_length' => 16,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(BigSerial::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesSerial(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => 'nextval(\'table_name_column_name_seq\'::regclass)',
                'is_nullable'              => 'NO',
                'data_type'                => 'integer',
                'character_maximum_length' => 16,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Serial::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesSmallSerial(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => 'nextval(\'table_name_column_name_seq\'::regclass)',
                'is_nullable'              => 'NO',
                'data_type'                => 'smallint',
                'character_maximum_length' => 16,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(SmallSerial::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesBigInt(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'bigint',
                'character_maximum_length' => 16,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(BigInt::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesInteger(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'integer',
                'character_maximum_length' => 16,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(IntegerType::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesSmallInt(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'smallint',
                'character_maximum_length' => 16,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(SmallInt::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesBit(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'bit',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Bit::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesBitWithLength(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'bit',
                'character_maximum_length' => 12,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Bit::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesBitVarying(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'bit varying',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(BitVarying::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesBitVaryingWithLength(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'bit varying',
                'character_maximum_length' => 12,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(BitVarying::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesBoolean(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'boolean',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Boolean::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesBox(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'box',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Box::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesByteA(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'bytea',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(ByteA::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesCharacter(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'character',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Character::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesCharacterWithMaximumLength(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'character',
                'character_maximum_length' => 12,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Character::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesCharacterVarying(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'character varying',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(CharacterVarying::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesCharacterVaryingWithMaximumLength(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'character varying',
                'character_maximum_length' => 12,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(CharacterVarying::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesCircle(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'circle',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Circle::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesCidr(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'cidr',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Cidr::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesDate(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'date',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Date::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesDoublePrecision(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'double precision',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(DoublePrecision::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesReal(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'real',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(FloatType::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesRealWithPrecision(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'real',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(FloatType::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesInet(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'inet',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Inet::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesJson(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'json',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Json::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesJsonB(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'jsonb',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(JsonB::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesLine(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'line',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Line::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesLseg(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'lseg',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Lseg::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesMacAddr(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'macaddr',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(MacAddr::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesMoney(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'money',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Money::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesNumeric(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'numeric',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Numeric::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesPath(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'path',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Path::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesPoint(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'point',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Point::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesPolygon(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'polygon',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Polygon::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesText(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'text',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Text::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimestampWithoutTimezone(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'timestamp without time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimestampWithoutTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimestampWithoutTimezoneWithPrecision(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'timestamp without time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimestampWithoutTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimestampWithTimezone(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'timestamp with time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimestampWithTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimestampWithTimezoneWithPrecision(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'timestamp with time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimestampWithTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimeWithoutTimezone(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'time without time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimeWithoutTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimeWithoutTimezoneWithPrecision(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'time without time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimeWithoutTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimeWithTimezone(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'time with time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimeWithTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesTimeWithTimezoneWithPrecision(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'time with time zone',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(TimeWithTimezone::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveResolvesUuid(): void
    {
        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'uuid',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ]),
        );

        $this->assertInstanceOf(Uuid::class, $this->resolver->resolve($columnInformation));
    }

    public function testResolveThrowsOnUnsupportedDataType(): void
    {
        $this->expectException(UnsupportedDataType::class);
        $this->expectExceptionMessage('Unsupported data type (`unsupported`) from specification information_schema');

        $columnInformation = new ColumnInformation(
            new Label('table_name'),
            new Label('column_name'),
            ColumnDefinition::fromInformationSchemaRecord([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'unsupported',
                'character_maximum_length' => null,
                'numeric_precision'        => 12,
                'numeric_scale'            => null,
            ]),
        );

        $this->resolver->resolve($columnInformation);
    }
}
