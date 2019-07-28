<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Constraint\PrimaryKey;
use PHPUnit\Framework\TestCase;

class AddPrimaryKeyTest extends TestCase
{
    public function testGetIndex(): void
    {
        $primaryKey = new PrimaryKey('index_name', 'column_name');

        $action = new AddPrimaryKey('table_name', $primaryKey);

        $this->assertSame($primaryKey, $action->getPrimaryKey());
    }

    public function testToQueries(): void
    {
        $primaryKey = new PrimaryKey('index_name', 'column_name');

        $queries = (new AddPrimaryKey('table_name', $primaryKey))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" ADD CONSTRAINT "index_name" PRIMARY KEY ("column_name")',
            $queries[0],
        );
    }
}
