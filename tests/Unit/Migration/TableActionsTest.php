<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Migration;

use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Migration\TableActions;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class TableActionsTest extends TestCase
{
    public function testGetName(): void
    {
        $tableActions = new TableActions(
            'TheName',
            new DropColumn(new Label('table_name'), new Label('column_name')),
            new DropTable(new Label('table_name')),
        );

        $this->assertSame('TheName', $tableActions->getName());
    }

    public function testIteratorImplementation(): void
    {
        $tableActions = new TableActions(
            'TheName',
            new DropColumn(new Label('table_name'), new Label('column_name')),
            new DropTable(new Label('table_name')),
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
