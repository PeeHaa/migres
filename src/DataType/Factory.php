<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

use PeeHaa\Migres\Exception\InvalidDataTypeSpecification;
use PeeHaa\Migres\Exception\UnsupportedDataType;

final class Factory
{
    public function buildFromSpecificationString(string $specification): Type
    {
        if (!preg_match('~(?P<type>[^\()]+)(?P<unit>\(.+\))?(?P<extraType>.+)?~', $specification, $matches)) {
            throw new InvalidDataTypeSpecification($specification, self::class);
        }

        $type = trim($matches['type']);

        if (isset($matches['extraType'])) {
            $type .= ' ' . $matches['extraType'];
        }

        $type = preg_replace('~\s+~', ' ', $type);

        switch ($type) {
            case 'smallint':
                return new SmallInt();

            case 'integer':
                return new IntegerType();

            case 'bigint':
                return new BigInt();

            case 'numeric':
            case 'decimal':
                return Numeric::fromString($specification);

            case 'real':
                return new Real();

            case 'double precision':
                return new DoublePrecision();

            case 'float':
                return FloatType::fromString($specification);

            case 'smallserial':
                return new SmallSerial();

            case 'serial':
                return new Serial();

            case 'bigserial':
                return new BigSerial();

            case 'money':
                return new Money();

            case 'character varying':
            case 'varchar':
                return CharacterVarying::fromString($specification);

            case 'character':
            case 'char':
                return Character::fromString($specification);

            case 'text':
                return new Text();

            case 'bytea':
                return new ByteA();

            case 'timestamp with time zone':
                return TimestampWithTimezone::fromString($specification);

            case 'timestamp without time zone':
            case 'timestamp':
                return TimestampWithoutTimezone::fromString($specification);

            case 'date':
                return new Date();

            case 'time with time zone':
                return TimeWithTimezone::fromString($specification);

            case 'time without time zone':
            case 'time':
                return TimeWithoutTimezone::fromString($specification);

            case 'boolean':
                return new Boolean();

            case 'point':
                return new Point();

            case 'line':
                return new Line();

            case 'lseg':
                return new Lseg();

            case 'box':
                return new Box();

            case 'path':
                return new Path();

            case 'polygon':
                return new Polygon();

            case 'circle':
                return new Circle();

            case 'cidr':
                return new Cidr();

            case 'inet':
                return new Inet();

            case 'macaddr':
                return new MacAddr();

            case 'bit':
                return Bit::fromString($specification);

            case 'bit varying':
                return BitVarying::fromString($specification);

            case 'uuid':
                return new Uuid();

            case 'json':
                return new Json();

            case 'jsonb':
                return new JsonB();

            default:
                throw new UnsupportedDataType($type, $specification);
        }
    }
}
