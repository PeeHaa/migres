<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class RenameTableTest extends TestCase
{
    public function testGetOldName(): void
    {
        $action = new RenameTable(new Label('old_table_name'), new Label('new_table_name'));

        $this->assertSame('old_table_name', $action->getOldName()->toString());
    }

    public function testGetNewName(): void
    {
        $action = new RenameTable(new Label('old_table_name'), new Label('new_table_name'));

        $this->assertSame('new_table_name', $action->getNewName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new RenameTable(new Label('old_table_name'), new Label('new_table_name')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "old_table_name" RENAME TO "new_table_name"',
            $queries[0],
        );
    }
}
