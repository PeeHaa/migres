<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class CreateTableTest extends TestCase
{
    public function testGetQueries(): void
    {
        $queries = (new CreateTable(new Label('table_name')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'CREATE TABLE "table_name" ()',
            $queries[0],
        );
    }
}
