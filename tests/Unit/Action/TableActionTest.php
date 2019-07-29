<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\TableAction;
use PHPUnit\Framework\TestCase;

class TableActionTest extends TestCase
{
    public function testGetTableName(): void
    {
        $action = new class('table_name') extends TableAction
        {
        };

        $this->assertSame('table_name', $action->getTableName());
    }
}
