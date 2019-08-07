<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddIndexByQuery;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddIndexByQueryTest extends TestCase
{
    public function testToQueries(): void
    {
        $queries = (new AddIndexByQuery(new Label('table_name'), 'CREATE INDEX "index_name" ON "table_name" (column_name)'))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'CREATE INDEX "index_name" ON "table_name" (column_name)',
            $queries[0],
        );
    }
}
