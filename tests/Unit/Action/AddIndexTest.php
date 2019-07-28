<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Constraint\Index;
use PHPUnit\Framework\TestCase;

class AddIndexTest extends TestCase
{
    public function testGetIndex(): void
    {
        $index = new Index('index_name', 'table_name', ['column_name']);

        $action = new AddIndex('table_name', $index);

        $this->assertSame($index, $action->getIndex());
    }

    public function testToQueries(): void
    {
        $index = new Index('index_name', 'table_name', ['column_name']);

        $queries = (new AddIndex('table_name', $index))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'CREATE INDEX "index_name" ON "table_name" (column_name)',
            $queries[0]
        );
    }
}
