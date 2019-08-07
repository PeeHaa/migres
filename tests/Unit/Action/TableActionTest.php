<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\TableAction;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class TableActionTest extends TestCase
{
    public function testGetTableName(): void
    {
        $action = new class(new Label('table_name')) extends TableAction
        {
        };

        $this->assertSame('table_name', $action->getTableName()->toString());
    }
}
