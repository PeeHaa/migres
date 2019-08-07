<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DropColumnTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropColumn(new Label('table_name'), new Label('column_name'));

        $this->assertSame('column_name', $action->getName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropColumn(new Label('table_name'), new Label('column_name')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP COLUMN "column_name"',
            $queries[0],
        );
    }
}
