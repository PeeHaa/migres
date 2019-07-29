<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Migration;

use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Migration\TableActions;
use PHPUnit\Framework\TestCase;

class TableActionsTest extends TestCase
{
    public function testGetName(): void
    {
        $tableActions = new TableActions(
            'TheName',
            new DropColumn('table_name', 'column_name'),
            new DropTable('table_name'),
        );

        $this->assertSame('TheName', $tableActions->getName());
    }

    public function testIteratorImplementation(): void
    {
        $tableActions = new TableActions(
            'TheName',
            new DropColumn('table_name', 'column_name'),
            new DropTable('table_name'),
        );

        $expectedResults = [
            DropColumn::class,
            DropTable::class,
        ];

        foreach ($tableActions as $i => $tableAction) {
            $this->assertInstanceOf($expectedResults[$i], $tableAction);
        }
    }
}
