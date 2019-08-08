<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddUniqueConstraintByQuery;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddUniqueConstraintByQueryTest extends TestCase
{
    public function testToQueries(): void
    {
        $queries = (new AddUniqueConstraintByQuery(new Label('table_name'), new Label('name_unique'), 'UNIQUE (column_name)'))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "name_unique" UNIQUE (column_name)',
            $queries[0],
        );
    }
}
