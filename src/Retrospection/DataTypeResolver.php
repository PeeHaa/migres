<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

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
use PeeHaa\Migres\DataType\Type;
use PeeHaa\Migres\DataType\Uuid;
use PeeHaa\Migres\Exception\UnsupportedDataType;

final class DataTypeResolver
{
    private Sequence $sequence;

    public function __construct(Sequence $sequence)
    {
        $this->sequence = $sequence;
    }

    public function resolve(ColumnInformation $columnInformation): Type
    {
        switch ($columnInformation->getColumnDefinition()->getDataType()) {
            case 'bigint':
            case 'integer':
            case 'smallint':
                return $this->getIntegerDataType($columnInformation);

            case 'bit':
                return new Bit($columnInformation->getColumnDefinition()->getMaximumLength());

            case 'bit varying':
                return new BitVarying($columnInformation->getColumnDefinition()->getMaximumLength());

            case 'boolean':
                return new Boolean();

            case 'box':
                return new Box();

            case 'bytea':
                return new ByteA();

            case 'character':
                return new Character($columnInformation->getColumnDefinition()->getMaximumLength());

            case 'character varying':
                return new CharacterVarying($columnInformation->getColumnDefinition()->getMaximumLength());

            case 'circle':
                return new Circle();

            case 'cidr':
                return new Cidr();

            case 'date':
                return new Date();

            case 'double precision':
                return new DoublePrecision();

            case 'real':
                return new FloatType($columnInformation->getColumnDefinition()->getNumericPrecision());

            case 'inet':
                return new Inet();

            case 'json':
                return new Json();

            case 'jsonb':
                return new JsonB();

            case 'line':
                return new Line();

            case 'lseg':
                return new Lseg();

            case 'macaddr':
                return new MacAddr();

            case 'money':
                return new Money();

            case 'numeric':
                return new Numeric($columnInformation->getColumnDefinition()->getNumericPrecision(), $columnInformation->getColumnDefinition()->getNumericScale());

            case 'path':
                return new Path();

            case 'point':
                return new Point();

            case 'polygon':
                return new Polygon();

            case 'text':
                return new Text();

            case 'timestamp without time zone':
                return new TimestampWithoutTimezone($columnInformation->getColumnDefinition()->getNumericPrecision());

            case 'timestamp with time zone':
                return new TimestampWithTimezone($columnInformation->getColumnDefinition()->getNumericPrecision());

            case 'time without time zone':
                return new TimeWithoutTimezone($columnInformation->getColumnDefinition()->getNumericPrecision());

            case 'time with time zone':
                return new TimeWithTimezone($columnInformation->getColumnDefinition()->getNumericPrecision());

            case 'uuid':
                return new Uuid();

            default:
                throw new UnsupportedDataType($columnInformation->getColumnDefinition()->getDataType(), 'information_schema');
        }
    }

    private function getIntegerDataType(ColumnInformation $columnInformation): Type
    {
        if ($this->sequence->isColumnUsingSequence($columnInformation) && $columnInformation->getColumnDefinition()->getDataType() === 'bigint') {
            return new BigSerial();
        }

        if ($this->sequence->isColumnUsingSequence($columnInformation) && $columnInformation->getColumnDefinition()->getDataType() === 'integer') {
            return new Serial();
        }

        if ($this->sequence->isColumnUsingSequence($columnInformation) && $columnInformation->getColumnDefinition()->getDataType() === 'smallint') {
            return new SmallSerial();
        }

        if ($columnInformation->getColumnDefinition()->getDataType() === 'bigint') {
            return new BigInt();
        }

        if ($columnInformation->getColumnDefinition()->getDataType() === 'integer') {
            return new IntegerType();
        }

        return new SmallInt();
    }
}
