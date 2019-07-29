<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Specification;

use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\Specification\Column;
use PHPUnit\Framework\TestCase;

class ColumnTest extends TestCase
{
    public function testGetName(): void
    {
        $column = new Column('column_name', new IntegerType());

        $this->assertSame('column_name', $column->getName());
    }

    public function testGetType(): void
    {
        $column = new Column('column_name', new IntegerType());

        $this->assertInstanceOf(IntegerType::class, $column->getType());
    }

    public function testNotNull(): void
    {
        $column = (new Column('column_name', new IntegerType()))
            ->notNull()
        ;

        $this->assertFalse($column->getOptions()->isNullable());
    }

    public function testDefault(): void
    {
        $column = (new Column('column_name', new IntegerType()))
            ->default(12)
        ;

        $this->assertSame('12', $column->getOptions()->getDefaultValue($column));
    }

    public function testToSqlWithoutOptions(): void
    {
        $column = new Column('column_name', new IntegerType());

        $this->assertSame('"column_name" integer', $column->toSql());
    }

    public function testToSqlWithNotNullOption(): void
    {
        $column = (new Column('column_name', new IntegerType()))
            ->notNull()
        ;

        $this->assertSame('"column_name" integer NOT NULL', $column->toSql());
    }

    public function testToSqlWithDefaultOption(): void
    {
        $column = (new Column('column_name', new IntegerType()))
            ->default(12)
        ;

        $this->assertSame('"column_name" integer DEFAULT 12', $column->toSql());
    }

    public function testToSqlWithNotNullAndDefaultOption(): void
    {
        $column = (new Column('column_name', new IntegerType()))
            ->notNull()
            ->default(12)
        ;

        $this->assertSame('"column_name" integer DEFAULT 12 NOT NULL', $column->toSql());
    }
}
