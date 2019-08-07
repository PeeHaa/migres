<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class ChangeColumnTest extends TestCase
{
    public function testGetName(): void
    {
        $column = new Column(new Label('column_name'), new IntegerType());

        $action = new ChangeColumn(new Label('table_name'), $column);

        $this->assertSame('column_name', $action->getName()->toString());
    }

    public function testToQueriesNullableNoDefault(): void
    {
        $column  = new Column(new Label('column_name'), new IntegerType());
        $queries = (new ChangeColumn(new Label('table_name'), $column))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(3, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" TYPE integer',
            $queries[0],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" DROP DEFAULT',
            $queries[1],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" DROP NOT NULL',
            $queries[2],
        );
    }

    public function testToQueriesNotNullNoDefault(): void
    {
        $column  = (new Column(new Label('column_name'), new IntegerType()))->notNull();
        $queries = (new ChangeColumn(new Label('table_name'), $column))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(3, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" TYPE integer',
            $queries[0],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" DROP DEFAULT',
            $queries[1],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" SET NOT NULL',
            $queries[2],
        );
    }

    public function testToQueriesNullableWithDefault(): void
    {
        $column  = (new Column(new Label('column_name'), new IntegerType()))->default(42);
        $queries = (new ChangeColumn(new Label('table_name'), $column))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(3, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" TYPE integer',
            $queries[0],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" SET DEFAULT 42',
            $queries[1],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" DROP NOT NULL',
            $queries[2],
        );
    }

    public function testToQueriesNotNullableWithDefault(): void
    {
        $column  = (new Column(new Label('column_name'), new IntegerType()))->notNull()->default(42);
        $queries = (new ChangeColumn(new Label('table_name'), $column))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(3, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" TYPE integer',
            $queries[0],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" SET DEFAULT 42',
            $queries[1],
        );

        $this->assertSame(
            'ALTER TABLE "table_name" ALTER COLUMN "column_name" SET NOT NULL',
            $queries[2],
        );
    }
}
