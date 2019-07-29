<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropColumn;
use PHPUnit\Framework\TestCase;

class DropColumnTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropColumn('table_name', 'column_name');

        $this->assertSame('column_name', $action->getName());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropColumn('table_name', 'column_name'))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP COLUMN "column_name"',
            $queries[0],
        );
    }
}
