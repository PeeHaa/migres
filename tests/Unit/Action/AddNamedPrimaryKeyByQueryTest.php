<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddNamedPrimaryKeyByQuery;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class AddNamedPrimaryKeyByQueryTest extends TestCase
{
    public function testToQueries(): void
    {
        $queries = (new AddNamedPrimaryKeyByQuery(new Label('table_name'), new Label('name_pkey'), 'PRIMARY KEY (column_name)'))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "name_pkey" PRIMARY KEY (column_name)',
            $queries[0],
        );
    }
}
