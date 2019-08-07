<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class RenameColumnTest extends TestCase
{
    public function testGetOldName(): void
    {
        $action = new RenameColumn(new Label('table_name'), new Label('column_old_name'), new Label('column_new_name'));

        $this->assertSame('column_old_name', $action->getOldName()->toString());
    }

    public function testGetNewName(): void
    {
        $action = new RenameColumn(new Label('table_name'), new Label('column_old_name'), new Label('column_new_name'));

        $this->assertSame('column_new_name', $action->getNewName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new RenameColumn(new Label('table_name'), new Label('column_old_name'), new Label('column_new_name')))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" RENAME COLUMN "column_old_name" TO "column_new_name"',
            $queries[0],
        );
    }
}
