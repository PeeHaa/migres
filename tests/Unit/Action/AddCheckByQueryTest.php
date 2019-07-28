<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddCheckByQuery;
use PHPUnit\Framework\TestCase;

class AddCheckByQueryTest extends TestCase
{
    public function testToQueries(): void
    {
        $queries = (new AddCheckByQuery('table_name', 'column_bigger_than_10', 'column > 10'))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "column_bigger_than_10" CHECK (column > 10)',
            $queries[0]
        );
    }
}
