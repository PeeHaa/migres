<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddPrimaryKeyTest extends TestCase
{
    public function testGetIndex(): void
    {
        $primaryKey = new PrimaryKey(new Label('index_name'), new Label('column_name'));

        $action = new AddPrimaryKey(new Label('table_name'), $primaryKey);

        $this->assertSame($primaryKey, $action->getPrimaryKey());
    }

    public function testToQueries(): void
    {
        $primaryKey = new PrimaryKey(new Label('index_name'), new Label('column_name'));

        $queries = (new AddPrimaryKey(new Label('table_name'), $primaryKey))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "index_name" PRIMARY KEY ("column_name")',
            $queries[0],
        );
    }
}
