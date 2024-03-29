<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DropTableTest extends TestCase
{
    public function testGetQueries(): void
    {
        $queries = (new DropTable(new Label('table_name')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'DROP TABLE "table_name"',
            $queries[0],
        );
    }
}
