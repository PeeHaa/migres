<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddForeignByQuery;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddForeignKeyByQueryTest extends TestCase
{
    public function testToQueries(): void
    {
        $action = new AddForeignByQuery(
            new Label('table_name'),
            new Label('name_fkey'),
            'FOREIGN KEY ("reference_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION',
        );

        $queries = $action->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "name_fkey" FOREIGN KEY ("reference_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION',
            $queries[0],
        );
    }
}
