<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Specification;

use PeeHaa\Migres\DataType\Bit;
use PeeHaa\Migres\DataType\CharacterVarying;
use PeeHaa\Migres\DataType\FloatType;
use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\Exception\InvalidDefaultValue;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Specification\ColumnOptions;
use PHPUnit\Framework\TestCase;

class ColumnOptionsTest extends TestCase
{
    public function testSetDefault(): void
    {
        $options = (new ColumnOptions())->setDefault('TheDefault');

        $this->assertSame("'TheDefault'", $options->getDefaultValue(new Column('column_name', new CharacterVarying())));
    }

    public function testHasDefaultWhenDefaultIsSet(): void
    {
        $options = (new ColumnOptions())->setDefault('TheDefault');

        $this->assertTrue($options->hasDefault());
    }

    public function testHasDefaultWhenDefaultIsNotSet(): void
    {
        $options = new ColumnOptions();

        $this->assertFalse($options->hasDefault());
    }

    public function testGetDefaultReturnsDefaultAsIsWhenItContainsACast(): void
    {
        $options = (new ColumnOptions())->setDefault("'TheDefault'::string");

        $this->assertSame(
            "'TheDefault'::string",
            $options->getDefaultValue(new Column('column_name', new CharacterVarying())),
        );
    }

    public function testGetDefaultReturnsDefaultAsIsWhenItContainsTheBinaryPrefix(): void
    {
        $options = (new ColumnOptions())->setDefault("B'100011'");

        $this->assertSame(
            "B'100011'",
            $options->getDefaultValue(new Column('column_name', new Bit())),
        );
    }

    public function testGetDefaultReturnsConvertedToBinaryDefault(): void
    {
        $options = (new ColumnOptions())->setDefault('100011');

        $this->assertSame(
            "B'100011'",
            $options->getDefaultValue(new Column('column_name', new Bit())),
            );
    }

    public function testGetDefaultThrowsOnInvalidBinaryDefault(): void
    {
        $options = (new ColumnOptions())->setDefault('x100011');

        $this->expectException(InvalidDefaultValue::class);

        $options->getDefaultValue(new Column('column_name', new Bit()));
    }

    public function testGetDefaultReturnsFormattedStringWhenTypeIsString(): void
    {
        $options = (new ColumnOptions())->setDefault('TheDefault');

        $this->assertSame(
            "'TheDefault'",
            $options->getDefaultValue(new Column('column_name', new CharacterVarying())),
        );
    }

    public function testGetDefaultReturnsFormattedStringWhenTypeIsBooleanTrue(): void
    {
        $options = (new ColumnOptions())->setDefault(true);

        $this->assertSame(
            "'true'",
            $options->getDefaultValue(new Column('column_name', new CharacterVarying())),
        );
    }

    public function testGetDefaultReturnsFormattedStringWhenTypeIsBooleanFalse(): void
    {
        $options = (new ColumnOptions())->setDefault(false);

        $this->assertSame(
            "'false'",
            $options->getDefaultValue(new Column('column_name', new CharacterVarying())),
        );
    }

    public function testGetDefaultReturnsFormattedStringWhenTypeIsInteger(): void
    {
        $options = (new ColumnOptions())->setDefault(12);

        $this->assertSame(
            '12',
            $options->getDefaultValue(new Column('column_name', new IntegerType())),
        );
    }

    public function testGetDefaultReturnsFormattedStringWhenTypeIsDouble(): void
    {
        $options = (new ColumnOptions())->setDefault(12.3);

        $this->assertSame(
            '12.3',
            $options->getDefaultValue(new Column('column_name', new FloatType())),
        );
    }

    public function testGetDefaultReturnsFormattedStringWhenTypeIsNull(): void
    {
        $options = (new ColumnOptions())->setDefault(null);

        $this->assertSame(
            'NULL',
            $options->getDefaultValue(new Column('column_name', new FloatType())),
        );
    }

    public function testNotNull(): void
    {
        $options = (new ColumnOptions())->notNull();

        $this->assertFalse($options->isNullable());
    }

    public function testHasOptionsReturnsFalseWhenNoOptionsHaveBeenSet(): void
    {
        $this->assertFalse((new ColumnOptions())->hasOptions());
    }

    public function testHasOptionsReturnsTrueWhenNullabilityHasBeenSet(): void
    {
        $this->assertTrue((new ColumnOptions())->notNull()->hasOptions());
    }

    public function testHasOptionsReturnsTrueWhenDefaultHasBeenSet(): void
    {
        $this->assertTrue((new ColumnOptions())->setDefault('TheDefault')->hasOptions());
    }

    public function testHasOptionsReturnsTrueWhenBothNullabilityAndDefaultHaveBeenSet(): void
    {
        $this->assertTrue((new ColumnOptions())->notNull()->setDefault('TheDefault')->hasOptions());
    }

    public function testToSqlWithoutOptions(): void
    {
        $this->assertSame(
            '',
            (new ColumnOptions())->toSql(new Column('column_name', new IntegerType())),
        );
    }

    public function testToSqlWithNullabilitySet(): void
    {
        $this->assertSame(
            'NOT NULL',
            (new ColumnOptions())->notNull()->toSql(new Column('column_name', new IntegerType())),
        );
    }

    public function testToSqlWithDefaultSet(): void
    {
        $this->assertSame(
            'DEFAULT 12',
            (new ColumnOptions())->setDefault(12)->toSql(new Column('column_name', new IntegerType())),
        );
    }

    public function testToSqlWithNullabilityDefaultSet(): void
    {
        $this->assertSame(
            'DEFAULT 12 NOT NULL',
            (new ColumnOptions())->notNull()->setDefault(12)->toSql(new Column('column_name', new IntegerType())),
        );
    }
}
