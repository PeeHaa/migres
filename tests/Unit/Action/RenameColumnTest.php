<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\RenameColumn;
use PHPUnit\Framework\TestCase;

class RenameColumnTest extends TestCase
{
    public function testGetOldName(): void
    {
        $action = new RenameColumn('table_name', 'column_old_name', 'column_new_name');

        $this->assertSame('column_old_name', $action->getOldName());
    }

    public function testGetNewName(): void
    {
        $action = new RenameColumn('table_name', 'column_old_name', 'column_new_name');

        $this->assertSame('column_new_name', $action->getNewName());
    }

    public function testGetQueries(): void
    {
        $queries = (new RenameColumn('table_name', 'column_old_name', 'column_new_name'))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" RENAME COLUMN "column_old_name" TO "column_new_name"',
            $queries[0],
        );
    }
}
