<?php declare(strict_types=1);

namespace Migres\Migrations;

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
use PeeHaa\Migres\DataType\Real;
use PeeHaa\Migres\DataType\Serial;
use PeeHaa\Migres\DataType\SmallInt;
use PeeHaa\Migres\DataType\SmallSerial;
use PeeHaa\Migres\DataType\Text;
use PeeHaa\Migres\DataType\TimestampWithoutTimezone;
use PeeHaa\Migres\DataType\TimestampWithTimezone;
use PeeHaa\Migres\DataType\TimeWithoutTimezone;
use PeeHaa\Migres\DataType\TimeWithTimezone;
use PeeHaa\Migres\DataType\Uuid;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class AddColumnTest extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('add_column_test', function (Table $table) {
            $table->addColumn('bigint', new BigInt());
            $table->addColumn('bigint_not_null', new BigInt())->notNull();
            $table->addColumn('bigint_default', new BigInt())->default(42);
            $table->addColumn('bigint_not_null_and_default', new BigInt())->notNull()->default(42);
            $table->addColumn('bigserial', new BigSerial());
            $table->addColumn('bigserial_not_null', new BigSerial())->notNull();
            $table->addColumn('bit', new Bit(12));
            $table->addColumn('bit_not_null', new Bit(12))->notNull();
            $table->addColumn('bit_default', new Bit(12))->default("B'111111111111'");
            $table->addColumn('bit_not_null_and_default', new Bit(12))->notNull()->default("B'111111111111'");
            $table->addColumn('varbit', new BitVarying(128));
            $table->addColumn('varbit_not_null', new BitVarying(128))->notNull();
            $table->addColumn('varbit_default', new BitVarying(128))->default("B'111111111111'");
            $table->addColumn('varbit_not_null_and_default', new BitVarying(128))->notNull()->default("B'111111111111'");
            $table->addColumn('boolean', new Boolean());
            $table->addColumn('box', new Box());
            $table->addColumn('byte', new ByteA());
            $table->addColumn('character', new Character());
            $table->addColumn('varchar', new CharacterVarying(128));
            $table->addColumn('varchar_not_null', new CharacterVarying(128))->notNull();
            $table->addColumn('varchar_default', new CharacterVarying(128))->default('Default value');
            $table->addColumn('varchar_not_null_and_default', new CharacterVarying(128))->notNull()->default('Default value');
            $table->addColumn('cidr', new Cidr());
            $table->addColumn('circle', new Circle());
            $table->addColumn('date', new Date());
            $table->addColumn('double', new DoublePrecision());
            $table->addColumn('float', new FloatType());
            $table->addColumn('inet', new Inet());
            $table->addColumn('integer', new IntegerType());
            $table->addColumn('json', new Json());
            $table->addColumn('jsonb', new JsonB());
            $table->addColumn('line', new Line());
            $table->addColumn('lseg', new Lseg());
            $table->addColumn('macaddr', new MacAddr());
            $table->addColumn('money', new Money());
            $table->addColumn('numeric', new Numeric());
            $table->addColumn('path', new Path());
            $table->addColumn('point', new Point());
            $table->addColumn('polygon', new Polygon());
            $table->addColumn('real', new Real());
            $table->addColumn('serial', new Serial());
            $table->addColumn('smallint', new SmallInt());
            $table->addColumn('smallserial', new SmallSerial());
            $table->addColumn('text', new Text());
            $table->addColumn('timestamp_without_timezone', new TimestampWithoutTimezone());
            $table->addColumn('timestamp_with_timezone', new TimestampWithTimezone());
            $table->addColumn('time_without_timezone', new TimeWithoutTimezone());
            $table->addColumn('time_with_timezone', new TimeWithTimezone());
            $table->addColumn('uuid', new Uuid());
        });
    }
}
