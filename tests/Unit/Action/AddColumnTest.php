<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddColumnTest extends TestCase
{
    public function testGetColumn(): void
    {
        $column = new Column(new Label('table_name'), new Label('column_name'), new IntegerType());

        $action = new AddColumn(new Label('table_name'), $column);

        $this->assertSame($column, $action->getColumn());
    }

    public function testToQueries(): void
    {
        $column  = new Column(new Label('table_name'), new Label('column_name'), new IntegerType());

        $queries = (new AddColumn(new Label('table_name'), $column))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD COLUMN "column_name" integer',
            $queries[0],
        );
    }

    public function testToQueriesWithOptions(): void
    {
        $column  = (new Column(new Label('table_name'), new Label('column_name'), new IntegerType()))->notNull();

        $queries = (new AddColumn(new Label('table_name'), $column))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD COLUMN "column_name" integer NOT NULL',
            $queries[0],
        );
    }
}
