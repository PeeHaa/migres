<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Constraint\Index;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddIndexTest extends TestCase
{
    public function testGetIndex(): void
    {
        $index = new Index(new Label('index_name'), new Label('table_name'), [new Label('column_name')]);

        $action = new AddIndex(new Label('table_name'), $index);

        $this->assertSame($index, $action->getIndex());
    }

    public function testToQueries(): void
    {
        $index = new Index(new Label('index_name'), new Label('table_name'), [new Label('column_name')]);

        $queries = (new AddIndex(new Label('table_name'), $index))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'CREATE INDEX "index_name" ON "table_name" (column_name)',
            $queries[0],
        );
    }
}
